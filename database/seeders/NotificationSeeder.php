<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            ['user_id' => 2, 'message' => 'Your order #1001 has been placed successfully.', 'type' => 'order',   'is_read' => 1, 'status' => 'active'],
            ['user_id' => 2, 'message' => 'Your order #1001 has been shipped.',              'type' => 'order',   'is_read' => 0, 'status' => 'active'],
            ['user_id' => 3, 'message' => 'Get 20% off on your next purchase! Use SAVE20.',  'type' => 'promo',   'is_read' => 0, 'status' => 'active'],
            ['user_id' => 3, 'message' => 'Welcome to our store! Explore our collection.',   'type' => 'general', 'is_read' => 1, 'status' => 'active'],
            ['user_id' => 2, 'message' => 'Your account password was changed recently.',     'type' => 'alert',   'is_read' => 0, 'status' => 'inactive'],
        ];

        foreach ($notifications as $notif) {
            Notification::create($notif);
        }
    }
}
