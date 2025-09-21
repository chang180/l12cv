<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 為現有的項目設置一些隨機的瀏覽數，用於測試
        Project::all()->each(function ($project) {
            $project->update([
                'views' => rand(5, 150) // 隨機設置 5-150 的瀏覽數
            ]);
        });
    }
}
