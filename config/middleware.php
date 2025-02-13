<?php
return [
    'is_admin' => \App\Http\Middleware\IsAdmin::class,
    'is_candidate' => \App\Http\Middleware\IsCandidate::class,
    'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
];
