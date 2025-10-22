<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

if (!function_exists('logActivity')) {
    function logActivity($action, $coach_id,  $module = null, $description = null)
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'coach_id' => $coach_id ?: null,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => Request::ip(),
            'device' => Request::header('User-Agent'),
        ]);
    }
}
