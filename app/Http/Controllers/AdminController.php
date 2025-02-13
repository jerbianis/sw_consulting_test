<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $job_post_ids = JobPost::where('user_id', auth()->user()->id)->pluck('id');

        $applications = Application::with('user')
            ->whereIn('job_post_id', $job_post_ids)
            ->get();

        return response()->json([
            'applications' => $applications,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string'],
        ]);

        $job = JobPost::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Job post created successfully',
            'job' => $job,
        ], 201);
    }

    public function update(Request $request, $job_post)
    {
        $job_post = JobPost::findOrFail($job_post);

        if (auth()->user()->id !== $job_post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string'],
        ]);

        $job_post->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Job post updated successfully',
            'job' => $job_post,
        ]);
    }

    public function destroy($job_post)
    {
        $job_post = JobPost::findOrFail($job_post);

        if (auth()->user()->id !== $job_post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job_post->delete();

        return response()->json([
            'message' => 'Job post deleted successfully',
        ]);
    }

    public function download_cv(Application $application)
    {
        $cvPath = storage_path('app/public/pdf/cvs/cv_' . $application->user->cv->id . '.pdf');

        if (file_exists($cvPath)) {
            return response()->download($cvPath, 'cv_' . $application->user->name . '_' .$application->user->id . '.pdf');
        }

        return response()->json(['message' => 'CV not found.'], 404);
    }

}
