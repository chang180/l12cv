<?php

namespace App\Support;

class ResumeTemplates
{
    public const DEFAULT = 'classic';

    /**
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return [
            'classic' => [
                'key' => 'classic',
                'name' => '經典專業',
                'description' => '清楚穩重的通用版型，適合多數履歷投遞情境。',
                'accent' => 'blue',
                'hero' => 'from-blue-500 to-purple-600',
            ],
            'modern' => [
                'key' => 'modern',
                'name' => '現代重點',
                'description' => '強調個人品牌與視覺層次，適合產品、設計與全端作品展示。',
                'accent' => 'teal',
                'hero' => 'from-teal-500 to-cyan-600',
            ],
            'compact' => [
                'key' => 'compact',
                'name' => '精簡掃描',
                'description' => '壓縮間距並提高資訊密度，適合經歷較多或需要快速掃讀的履歷。',
                'accent' => 'slate',
                'hero' => 'from-slate-700 to-slate-900',
            ],
        ];
    }

    public static function isValid(?string $key): bool
    {
        return array_key_exists((string) $key, self::all());
    }

    /**
     * @return array<string, string>
     */
    public static function resolve(?string $key): array
    {
        return self::all()[$key] ?? self::all()[self::DEFAULT];
    }

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(self::all());
    }

    /**
     * @return array<string, string>
     */
    public static function publicClasses(?string $key): array
    {
        return match (self::resolve($key)['key']) {
            'modern' => [
                'page' => 'bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-100 dark:from-slate-950 dark:via-slate-900 dark:to-teal-950',
                'hero' => 'from-teal-500 to-cyan-600',
                'sectionEducation' => 'from-cyan-500 to-teal-600',
                'sectionExperience' => 'from-emerald-500 to-teal-600',
                'card' => 'rounded-xl',
                'spacing' => 'mb-6 sm:mb-8',
            ],
            'compact' => [
                'page' => 'bg-slate-100 dark:bg-slate-950',
                'hero' => 'from-slate-700 to-slate-900',
                'sectionEducation' => 'from-slate-600 to-slate-800',
                'sectionExperience' => 'from-zinc-600 to-slate-800',
                'card' => 'rounded-lg',
                'spacing' => 'mb-4 sm:mb-5',
            ],
            default => [
                'page' => 'bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900',
                'hero' => 'from-blue-500 to-purple-600',
                'sectionEducation' => 'from-blue-500 to-indigo-600',
                'sectionExperience' => 'from-green-500 to-emerald-600',
                'card' => 'rounded-2xl',
                'spacing' => 'mb-6 sm:mb-8',
            ],
        };
    }

    /**
     * @return array<string, array<int, int>|string>
     */
    public static function pdfTheme(?string $key): array
    {
        return match (self::resolve($key)['key']) {
            'modern' => [
                'primary' => [13, 148, 136],
                'secondary' => [8, 145, 178],
                'align' => 'L',
                'order' => 'experience-first',
            ],
            'compact' => [
                'primary' => [51, 65, 85],
                'secondary' => [71, 85, 105],
                'align' => 'L',
                'order' => 'education-first',
            ],
            default => [
                'primary' => [102, 126, 234],
                'secondary' => [23, 162, 184],
                'align' => 'C',
                'order' => 'education-first',
            ],
        };
    }
}
