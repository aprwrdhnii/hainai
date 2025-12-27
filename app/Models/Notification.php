<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public static function notify($type, $title, $message, $icon = 'bi-bell', $color = 'blue', $link = null)
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'link' => $link,
        ]);
    }

    public static function unreadCount()
    {
        return self::where('is_read', false)->count();
    }

    public static function latest10()
    {
        return self::orderBy('created_at', 'desc')->take(10)->get();
    }
}
