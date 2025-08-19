<?php

if (!function_exists('money')) {
    function money($amount, $currency = '₹'): string
    {
        return $currency . ' ' . number_format((float) $amount, 2);
    }
}

if (!function_exists('format_phone')) {
    function format_phone($phone): string
    {
        return $phone;
    }
}