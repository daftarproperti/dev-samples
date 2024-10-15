<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function formatCurrency($amount)
    {
        // Remove any non-numeric characters except for dots
        $number = preg_replace('/[^\d.]/', '', $amount);

        if ($number >= 1000000000000) {
            // For Triliun
            $formatted = round($number / 1000000000000, 0) . ' Triliun';
        } elseif ($number >= 1000000000) {
            // For Miliar
            $formatted = round($number / 1000000000, 0) . ' Miliar';
        } elseif ($number >= 1000000) {
            // For Juta
            $formatted = round($number / 1000000, 0) . ' Juta';
        } else {
            // Return original if less than 1 million
            $formatted = $amount;
        }

        return 'Rp ' . $formatted;
    }
}
