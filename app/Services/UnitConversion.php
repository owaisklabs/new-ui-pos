<?php

namespace App\Services;

class UnitConversion
{
    public static function toBase($packs, $loose, $factor): float
    {
        return ((float) $packs * (float) $factor) + (float) $loose;
    }

    public static function format($packs, $loose, ?string $packUnit, ?string $baseUnit): string
    {
        $packUnit = $packUnit ?: 'Roll';
        $baseUnit = $baseUnit ?: 'Feet';
        $parts = [];

        if ((int) $packs > 0) {
            $parts[] = (int) $packs . ' ' . $packUnit . ((int) $packs === 1 ? '' : 's');
        }
        if ((float) $loose > 0 || empty($parts)) {
            $parts[] = ((float) $loose + 0) . ' ' . $baseUnit;
        }

        return implode(' + ', $parts);
    }
}
