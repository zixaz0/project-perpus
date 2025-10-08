<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogger
{
    protected static array $sensitive = ['password', 'remember_token', 'api_token', 'token'];

    protected static function sanitize($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        foreach (self::$sensitive as $k) {
            if (array_key_exists($k, $data)) {
                $data[$k] = '[REDACTED]';
            }
        }
        return $data;
    }

    public static function log(string $activity, $subject = null, $old = null, $new = null, $user = null, Request $request = null)
    {
        $request = $request ?? request();
        $user = $user ?? Auth::user();

        if (is_array($old)) {
            $old = self::sanitize($old);
        }
        if (is_array($new)) {
            $new = self::sanitize($new);
        }

        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'activity' => $activity,
            'subject_type' => is_object($subject) ? get_class($subject) : null,
            'subject_id' => (is_object($subject) && method_exists($subject, 'getKey')) ? $subject->getKey() : null,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
        ]);
    }
}