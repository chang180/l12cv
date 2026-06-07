<?php

namespace App\Support;

class ResumeExperience
{
    /**
     * Sort experience entries with the most recent first.
     *
     * @param  array<int, array<string, mixed>>  $experience
     * @return array<int, array<string, mixed>>
     */
    public static function sort(array $experience): array
    {
        usort($experience, fn (array $a, array $b): int => strcmp(
            self::sortKey($b),
            self::sortKey($a),
        ));

        return array_values($experience);
    }

    /**
     * @param  array<string, mixed>  $experience
     */
    private static function sortKey(array $experience): string
    {
        if ($experience['current'] ?? false) {
            return '9999-12-31';
        }

        return $experience['end_date'] ?? $experience['start_date'] ?? '0000-01-01';
    }
}
