<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] dark:bg-grid-slate-700/25 dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]"></div>
            
            <!-- Floating Elements -->
            <div class="absolute top-20 left-10 w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-gradient-to-r from-green-400 to-blue-400 rounded-full opacity-20 animate-bounce"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full opacity-20 animate-pulse"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur opacity-75 animate-pulse"></div>
                            <x-application-logo class="relative w-24 h-24 sm:w-32 sm:h-32" />
                        </div>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent mb-6">
                        {{ config('app.name') }}
                    </h1>
                    
                    <p class="text-xl sm:text-2xl text-slate-600 dark:text-slate-300 mb-4 max-w-3xl mx-auto">
                        打造專業履歷，開啟職涯新篇章
                    </p>
                    
                    <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-12">
                        使用現代化的履歷建構工具，輕鬆創建、管理和分享你的專業履歷，讓每一次求職都更加成功
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        @auth
                            <a href="{{ route('resume.dashboard') }}" 
                               class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <span class="relative z-10">管理我的履歷</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <span class="relative z-10">立即開始</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                            <a href="{{ route('register') }}" 
                               class="px-8 py-4 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-300">
                                免費註冊
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    為什麼選擇我們？
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    我們提供最現代化的履歷建構工具，讓你的專業履歷脫穎而出
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="group relative p-8 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-r from-blue-500/10 to-purple-500/10 rounded-full -translate-y-10 translate-x-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">專業模板</h3>
                        <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors duration-300">多種精心設計的履歷模板，適合不同行業和職位需求</p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="group relative p-8 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-r from-green-500/10 to-blue-500/10 rounded-full -translate-y-10 translate-x-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-3 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">快速建構</h3>
                        <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors duration-300">直觀的編輯界面，幾分鐘就能完成專業履歷</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="group relative p-8 bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-r from-purple-500/10 to-pink-500/10 rounded-full -translate-y-10 translate-x-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-3 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">輕鬆分享</h3>
                        <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors duration-300">一鍵生成分享連結，讓雇主輕鬆查看你的履歷</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Resumes Section -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-r from-blue-400/10 to-purple-400/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-r from-green-400/10 to-blue-400/10 rounded-full translate-y-12 -translate-x-12"></div>
            
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">
                    最新履歷
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400">
                    看看其他用戶創建的精彩履歷
                </p>
            </div>
            
            <div class="relative bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">熱門履歷</h3>
                        @auth
                            <a href="{{ route('resume.dashboard') }}"
                                class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                <span class="relative z-10">管理我的履歷</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                <span class="relative z-10">登入管理履歷</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </a>
                        @endauth
                    </div>
                    
                    <livewire:resume.latest-list />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-slate-200/50 dark:border-slate-700/50">
            <div class="text-center space-y-4">
                <div class="flex justify-center space-x-6">
                    <a href="https://github.com/chang180/l12cv" target="_blank" 
                       class="group flex items-center space-x-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors duration-300">
                        <i class="fab fa-github text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="text-sm font-medium">GitHub Repository</span>
                    </a>
                    <a href="https://laravel.com" target="_blank" 
                       class="group flex items-center space-x-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors duration-300">
                        <i class="fab fa-laravel text-xl text-red-500 group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="text-sm font-medium">Laravel Framework</span>
                    </a>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) 
                    <span class="mx-2">•</span>
                    建構於 Laravel 框架之上
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
