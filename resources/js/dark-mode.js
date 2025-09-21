// 全域 Dark Mode 管理
document.addEventListener('DOMContentLoaded', function() {
    // 初始化 dark mode 狀態
    const initDarkMode = () => {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
            return 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            return 'light';
        }
    };

    // 切換 dark mode
    const toggleDarkMode = () => {
        const isDark = document.documentElement.classList.contains('dark');
        
        if (isDark) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            return 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            return 'dark';
        }
    };

    // 獲取當前主題
    const getCurrentTheme = () => {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    };

    // 初始化
    const currentTheme = initDarkMode();

    // 創建全域 dark mode 管理物件
    window.DarkModeManager = {
        toggle: toggleDarkMode,
        getCurrent: getCurrentTheme,
        isDark: () => getCurrentTheme() === 'dark',
        setTheme: (theme) => {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    };

    // 監聽系統主題變化
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});
