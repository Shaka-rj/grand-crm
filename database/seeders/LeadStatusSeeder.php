<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Yangi',
                'slug' => 'new',
                'color' => '#3B82F6',
                'icon' => 'fa-solid fa-plus'
            ],
            [
                'name' => 'Ko‘tarmadi',
                'slug' => 'no-answer',
                'color' => '#F59E0B',
                'icon' => 'fa-solid fa-phone-slash'
            ],
            [
                'name' => 'Rad etildi',
                'slug' => 'rejected',
                'color' => '#EF4444',
                'icon' => 'fa fa-ban'
            ],
            [
                'name' => 'O‘ylab ko‘radi',
                'slug' => 'thinking',
                'color' => '#8B5CF6',
                'icon' => 'fa-regular fa-clock'
            ],
            [
                'name' => 'To‘lov amalga oshirdi',
                'slug' => 'paid',
                'color' => '#10B981',
                'icon' => 'fa fa-check-circle'
            ],
            [
                'name' => 'Ish jarayonida',
                'slug' => 'in-progress',
                'color' => '#06B6D4',
                'icon' => 'fa-regular fa-calendar-check'
            ],
            [
                'name' => 'Muvaffaqiyatli yakunlandi',
                'slug' => 'completed',
                'color' => '#22C55E',
                'icon' => 'fa fa-stop'
            ],
            [
                'name' => 'Aloqaga chiqilmadi',
                'slug' => 'unreachable',
                'color' => '#6B7280',
                'icon' => 'fa-regular fa-square-check'
            ],
            [
                'name' => 'Qayta bog‘lanish kerak',
                'slug' => 'callback',
                'color' => '#EC4899',
                'icon' => 'fa fa-repeat'
            ],
        ];

        foreach ($statuses as $status) {
            LeadStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}