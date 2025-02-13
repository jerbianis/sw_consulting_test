<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cv->user->name }}'s CV</title>
</head>
<body>
<h1>{{ $cv->user->name }}'s CV</h1>

<h2>Introduction</h2>
<p>{{ $cv->introduction }}</p>

<h2>Experience</h2>
<p>{{ $cv->experience }}</p>

<h2>Skills</h2>
<p>{{ $cv->skills }}</p>

<h2>Education</h2>
<p>{{ $cv->education }}</p>
</body>
</html>
