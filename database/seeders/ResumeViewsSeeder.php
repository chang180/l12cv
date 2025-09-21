<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resume;

class ResumeViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 為現有的履歷設置一些隨機的瀏覽數，用於測試
        Resume::all()->each(function ($resume) {
            $resume->update([
                'views' => rand(10, 200) // 隨機設置 10-200 的瀏覽數
            ]);
        });
    }
}
