<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <!-- Left Side - Hero Section -->
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-r dark:border-slate-700">
                <!-- Animated Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                
                <!-- Floating Elements -->
                <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl animate-pulse"></div>
                <div class="absolute top-40 right-20 w-32 h-32 bg-purple-400/20 rounded-full blur-2xl animate-bounce" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-40 left-20 w-16 h-16 bg-blue-400/30 rounded-full blur-lg animate-pulse" style="animation-delay: 2s;"></div>
                
                <!-- Logo and Brand -->
                <div class="relative z-20 mb-12">
                    <a href="{{ route('home') }}" class="flex items-center text-xl font-bold" wire:navigate>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm mr-4">
                            <i class="fas fa-file-alt text-2xl text-white"></i>
                        </div>
                        <span class="bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                            {{ config('app.name', 'Laravel') }}
                        </span>
                    </a>
                </div>

                <!-- Main Content -->
                <div class="relative z-20 flex-1 flex flex-col justify-center">
                    <div class="space-y-8">
                        <!-- Icon and Title -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="fas fa-user-shield text-2xl text-white"></i>
                                </div>
                                <h2 class="text-3xl font-bold">安全登入</h2>
                            </div>
                            <p class="text-blue-100 text-lg leading-relaxed">
                                保護您的履歷資料，享受安全的線上履歷管理體驗
                            </p>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-shield-alt text-blue-300"></i>
                                <span class="text-white/90">端到端加密保護</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mobile-alt text-blue-300"></i>
                                <span class="text-white/90">跨裝置同步</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clock text-blue-300"></i>
                                <span class="text-white/90">24/7 可用性</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote Section -->
                <div class="relative z-20 mt-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                        <div class="flex items-start space-x-4">
                            <div class="p-2 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full">
                                <i class="fas fa-quote-left text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <blockquote class="text-white/90 text-lg leading-relaxed">
                                    "成功不是終點，失敗不是末日，最重要的是繼續前進的勇氣。"
                                </blockquote>
                                <footer class="text-blue-200 text-sm mt-3 font-medium">
                                    — 溫斯頓·邱吉爾
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form Section -->
            <div class="w-full lg:p-8 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[400px]">
                    <!-- Mobile Logo -->
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-3 font-medium lg:hidden mb-8" wire:navigate>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
                            {{ config('app.name', 'Laravel') }}
                        </span>
                    </a>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
