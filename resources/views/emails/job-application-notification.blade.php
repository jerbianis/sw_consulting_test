<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Job Application</title>
</head>
<body>
    <h1>New Job Application</h1>
    <p>Dear Admin,</p>
    <p>You have received a new job application from {{ $user->name }} for the job titled "{{ $jobPost->title }}".</p>
    <p>Attached is the candidate's CV for your review.</p>
    <p>Best regards,</p>
</body>
</html>
