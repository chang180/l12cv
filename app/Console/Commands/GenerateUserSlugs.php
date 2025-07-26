<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateUserSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '為所有現有用戶生成唯一的 slug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('slug')->get();
        
        $this->info("開始為 {$users->count()} 個用戶生成 slug...");
        
        foreach ($users as $user) {
            $slug = Str::slug($user->name);
            
            // 確保 slug 是唯一的
            $originalSlug = $slug;
            $count = 1;
            
            while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $originalSlug . '-' . Str::random(5);
                $count++;
            }
            
            $user->slug = $slug;
            $user->save();
            
            $this->info("用戶 {$user->name} 的 slug 設置為: {$slug}");
        }
        
        $this->info('所有用戶的 slug 已成功生成！');
    }
}