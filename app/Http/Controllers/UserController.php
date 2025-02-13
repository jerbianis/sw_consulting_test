<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationNotification;
use App\Models\Application;
use App\Models\Cv;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPdf\Facades\Pdf;

class UserController extends Controller
{
    public function create_cv(Request $request)
    {
        $request->validate([
            'introduction' => 'required|string',
            'experience' => 'required|string',
            'skills' => 'required|string',
            'education' => 'required|string',
        ]);

        $cv = Cv::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'introduction' => $request->introduction,
                'experience' => $request->experience,
                'skills' => $request->skills,
                'education' => $request->education,
            ]
        );

        Pdf::view('pdf.cv_template',[
            'cv' => $cv,
        ])->save(storage_path('app/public/pdf/cvs/cv_'.$cv->id.'.pdf'));

        return response()->json([
            'message' => 'CV saved successfully',
            'cv' => $cv,
        ], 201);
    }

    public function index()
    {
        $cv = auth()->user()->cv;
        if (!$cv) {
            return response()->json(['message' => 'You must create a CV before seeing job posts.'], 400);
        }

        $jobs = JobPost::all();
        return response()->json([
            'jobs' => $jobs
        ]);
    }

    public function apply($job_post)
    {
        $cv = auth()->user()->cv;
        if (!$cv) {
            return response()->json(['message' => 'You must create a CV before applying for jobs.'], 400);
        }

        $job_post = JobPost::findOrFail($job_post);

        $existingApplication = Application::where('user_id', auth()->user()->id)
            ->where('job_post_id', $job_post->id)
            ->first();
        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied for this job.'], 400);
        }

        Application::create([
            'user_id' => auth()->user()->id,
            'job_post_id' => $job_post->id,
        ]);

        $cvPath = storage_path('app/public/pdf/cvs/cv_' . $cv->id . '.pdf');
        $to = $job_post->user->email;
        Mail::to($to)->send(new JobApplicationNotification(auth()->user(), $job_post, $cvPath));

        return response()->json([
            'message' => 'Application submitted successfully'
        ]);
    }
}
