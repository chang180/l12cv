<?php

namespace App\Helpers;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownHelper
{
    private static $converter = null;

    /**
     * 獲取 Markdown 轉換器實例
     */
    public static function getConverter(): MarkdownConverter
    {
        if (self::$converter === null) {
            $environment = new Environment([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);

            $environment->addExtension(new CommonMarkCoreExtension);
            $environment->addExtension(new GithubFlavoredMarkdownExtension);

            self::$converter = new MarkdownConverter($environment);
        }

        return self::$converter;
    }

    /**
     * 將 Markdown 轉換為 HTML
     */
    public static function toHtml(string $markdown): string
    {
        if (empty(trim($markdown))) {
            return '';
        }

        return self::getConverter()->convert($markdown)->getContent();
    }

    /**
     * 獲取支援暗黑模式的 Markdown HTML
     */
    public static function toHtmlWithDarkMode(string $markdown): string
    {
        $html = self::toHtml($markdown);

        if (empty($html)) {
            return '';
        }

        // 添加暗黑模式支援的 CSS 類
        $html = str_replace('<h1>', '<h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">', $html);
        $html = str_replace('<h2>', '<h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3 mt-6">', $html);
        $html = str_replace('<h3>', '<h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2 mt-4">', $html);
        $html = str_replace('<h4>', '<h4 class="text-base font-semibold text-slate-900 dark:text-white mb-2 mt-3">', $html);
        $html = str_replace('<h5>', '<h5 class="text-sm font-semibold text-slate-900 dark:text-white mb-1 mt-2">', $html);
        $html = str_replace('<h6>', '<h6 class="text-xs font-semibold text-slate-900 dark:text-white mb-1 mt-2">', $html);

        $html = str_replace('<p>', '<p class="text-slate-700 dark:text-slate-300 mb-3 leading-relaxed">', $html);
        $html = str_replace('<ul>', '<ul class="list-disc list-inside text-slate-700 dark:text-slate-300 mb-3 space-y-1">', $html);
        $html = str_replace('<ol>', '<ol class="list-decimal list-inside text-slate-700 dark:text-slate-300 mb-3 space-y-1">', $html);
        $html = str_replace('<li>', '<li class="text-slate-700 dark:text-slate-300">', $html);

        $html = str_replace('<blockquote>', '<blockquote class="border-l-4 border-blue-500 dark:border-blue-400 pl-4 py-2 my-4 bg-blue-50 dark:bg-blue-900/20 text-slate-700 dark:text-slate-300 italic">', $html);

        $html = str_replace('<code>', '<code class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 px-2 py-1 rounded text-sm font-mono">', $html);
        $html = str_replace('<pre>', '<pre class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-4 rounded-lg overflow-x-auto my-4">', $html);
        $html = str_replace('<pre class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-4 rounded-lg overflow-x-auto my-4"><code', '<pre class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-4 rounded-lg overflow-x-auto my-4"><code class="bg-transparent text-slate-800 dark:text-slate-200 px-0 py-0 rounded-none text-sm font-mono"', $html);

        $html = str_replace('<a ', '<a class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline" ', $html);
        $html = str_replace('<strong>', '<strong class="font-semibold text-slate-900 dark:text-white">', $html);
        $html = str_replace('<em>', '<em class="italic text-slate-700 dark:text-slate-300">', $html);

        $html = str_replace('<table>', '<table class="w-full border-collapse border border-slate-300 dark:border-slate-600 my-4">', $html);
        $html = str_replace('<th>', '<th class="border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white px-4 py-2 text-left font-semibold">', $html);
        $html = str_replace('<td>', '<td class="border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 px-4 py-2">', $html);

        return $html;
    }

    /**
     * 獲取 Markdown 預覽的 HTML（用於編輯器）
     */
    public static function getPreviewHtml(string $markdown): string
    {
        if (empty(trim($markdown))) {
            return '<div class="text-slate-500 dark:text-slate-400 italic text-sm">開始輸入 Markdown 內容...</div>';
        }

        return '<div class="prose prose-slate dark:prose-invert max-w-none">'.self::toHtmlWithDarkMode($markdown).'</div>';
    }
}
