<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- Logo Section -->
            <div class="flex items-center space-x-3 px-3 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                    <i class="fas fa-file-alt text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg text-zinc-900 dark:text-zinc-100">{{ config('app.name') }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">履歷管理系統</span>
                </div>
            </div>

            <!-- Navigation -->
            <flux:navlist variant="outline" class="mt-4">
                <flux:navlist.group heading="主要功能" class="grid">
                    <flux:navlist.item 
                        icon="home" 
                        href="/resume" 
                        :current="request()->routeIs('resume.dashboard')" 
                        wire:navigate
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-tachometer-alt text-blue-500"></i>
                            <span>控制台</span>
                        </div>
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="履歷管理" class="grid mt-4">
                    <flux:navlist.item 
                        icon="document-text" 
                        :href="route('resume.edit')" 
                        :current="request()->routeIs('resume.edit')" 
                        wire:navigate
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-edit text-green-500"></i>
                            <span>編輯履歷</span>
                        </div>
                    </flux:navlist.item>
                    
                    
                </flux:navlist.group>

                <flux:navlist.group heading="帳戶設定" class="grid mt-4">
                    <flux:navlist.item 
                        icon="user" 
                        href="/settings/profile" 
                        :current="request()->routeIs('settings.profile')" 
                        wire:navigate
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user text-indigo-500"></i>
                            <span>個人資料</span>
                        </div>
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="lock-closed" 
                        href="/settings/password" 
                        :current="request()->routeIs('settings.password')" 
                        wire:navigate
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-lock text-red-500"></i>
                            <span>密碼設定</span>
                        </div>
                    </flux:navlist.item>
                    
                    <flux:navlist.item 
                        icon="paint-brush" 
                        href="/settings/appearance" 
                        :current="request()->routeIs('settings.appearance')" 
                        wire:navigate
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-palette text-pink-500"></i>
                            <span>外觀設定</span>
                        </div>
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
