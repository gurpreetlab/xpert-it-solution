<?php

namespace App\Support;

class IndianNumberToWords
{
    private static array $ones = [
        "",
        "One",
        "Two",
        "Three",
        "Four",
        "Five",
        "Six",
        "Seven",
        "Eight",
        "Nine",
        "Ten",
        "Eleven",
        "Twelve",
        "Thirteen",
        "Fourteen",
        "Fifteen",
        "Sixteen",
        "Seventeen",
        "Eighteen",
        "Nineteen",
    ];

    private static array $tens = [
        "",
        "",
        "Twenty",
        "Thirty",
        "Forty",
        "Fifty",
        "Sixty",
        "Seventy",
        "Eighty",
        "Ninety",
    ];

    /**
     * Converts a rupee amount (e.g. 4399.00) into words using the
     * Indian numbering system (lakh/crore), as required on a GST invoice.
     */
    public static function convert(float $amount): string
    {
        $rupees = (int) floor($amount);
        $paise = (int) round(($amount - $rupees) * 100);

        $words = "Rupees " . self::convertWholeNumber($rupees) . " Only";

        if ($paise > 0) {
            $words =
                "Rupees " .
                self::convertWholeNumber($rupees) .
                " and " .
                self::convertWholeNumber($paise) .
                " Paise Only";
        }

        return $words;
    }

    private static function convertWholeNumber(int $number): string
    {
        if ($number === 0) {
            return "Zero";
        }

        $crore = intdiv($number, 10000000);
        $number %= 10000000;
        $lakh = intdiv($number, 100000);
        $number %= 100000;
        $thousand = intdiv($number, 1000);
        $number %= 1000;
        $hundred = intdiv($number, 100);
        $remainder = $number % 100;

        $parts = [];

        if ($crore > 0) {
            $parts[] = self::convertTwoDigits($crore) . " Crore";
        }
        if ($lakh > 0) {
            $parts[] = self::convertTwoDigits($lakh) . " Lakh";
        }
        if ($thousand > 0) {
            $parts[] = self::convertTwoDigits($thousand) . " Thousand";
        }
        if ($hundred > 0) {
            $parts[] = self::$ones[$hundred] . " Hundred";
        }
        if ($remainder > 0) {
            $parts[] = self::convertTwoDigits($remainder);
        }

        return implode(" ", $parts);
    }

    private static function convertTwoDigits(int $number): string
    {
        if ($number < 20) {
            return self::$ones[$number];
        }

        $ten = intdiv($number, 10);
        $one = $number % 10;

        return trim(self::$tens[$ten] . " " . self::$ones[$one]);
    }
}
