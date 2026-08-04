<?php

namespace App\Support;

class CategoryVisuals
{
    private const array MAP = [
        'Networking' => [
            'icon' => 'wifi',
            'pill' => 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/20 border-blue-100 dark:border-blue-900/30',
            'gradient' => ['from-blue-900', 'from-blue-800'],
        ],
        'CCTV & Security' => [
            'icon' => 'video-camera',
            'pill' => 'text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/30',
            'gradient' => ['from-emerald-900', 'from-emerald-800'],
        ],
        'Storage' => [
            'icon' => 'circle-stack',
            'pill' => 'text-purple-600 dark:text-purple-400 bg-purple-50/50 dark:bg-purple-950/20 border-purple-100 dark:border-purple-900/30',
            'gradient' => ['from-purple-900', 'from-purple-800'],
        ],
        'Computer Peripherals' => [
            'icon' => 'computer-desktop',
            'pill' => 'text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/20 border-amber-100 dark:border-amber-900/30',
            'gradient' => ['from-amber-900', 'from-amber-800'],
        ],
        'Power & Accessories' => [
            'icon' => 'bolt',
            'pill' => 'text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/20 border-orange-100 dark:border-orange-900/30',
            'gradient' => ['from-orange-900', 'from-orange-800'],
        ],
        'Printing' => [
            'icon' => 'printer',
            'pill' => 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/20 border-indigo-100 dark:border-indigo-900/30',
            'gradient' => ['from-indigo-900', 'from-indigo-800'],
        ],
        'Laptops' => [
            'icon' => 'cpu-chip',
            'pill' => 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/20 border-blue-100 dark:border-blue-900/30',
            'gradient' => ['from-blue-900', 'from-blue-800'],
        ],

    ];

    private const string DEFAULT_ICON = 'square-3-stack-3d';

    private const string DEFAULT_PILL = 'text-zinc-600 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800';

    private const array DEFAULT_GRADIENT = ['from-zinc-800', 'from-zinc-700'];

    public static function icon(?string $categoryName): string
    {
        return self::MAP[$categoryName]['icon'] ?? self::DEFAULT_ICON;
    }

    public static function pillClasses(?string $categoryName): string
    {
        return self::MAP[$categoryName]['pill'] ?? self::DEFAULT_PILL;
    }

    public static function gradient(
        ?string $categoryName,
        bool $muted = false,
    ): array {
        $from =
            self::MAP[$categoryName]['gradient'][$muted ? 1 : 0] ??
            self::DEFAULT_GRADIENT[$muted ? 1 : 0];

        return [$from, $muted ? 'to-zinc-900' : 'to-zinc-950'];
    }
}
