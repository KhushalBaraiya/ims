<?php

use App\Models\Currency;

if (!function_exists('current_currency')) {
    /**
     * Retrieve the current globally active currency model.
     */
    function current_currency()
    {
        $currency = session('active_currency');
        if (!$currency) {
            $currency = Currency::where('is_default', true)->first();
            if (!$currency) {
                $currency = Currency::first();
            }
            if ($currency) {
                session(['active_currency' => $currency]);
            }
        }
        return $currency;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a given currency amount based on the active currency and its exchange rate.
     */
    function format_currency($amount)
    {
        $currency = current_currency();
        $converted = $amount;
        $symbol = '$';
        if ($currency) {
            $rate = $currency->exchange_rate;
            if ($rate > 0) {
                $converted = $amount / $rate;
            }
            $symbol = $currency->symbol;
        }
        return $symbol . number_format($converted, 2);
    }
}

if (!function_exists('get_active_currencies')) {
    /**
     * Fetch all active currencies.
     */
    function get_active_currencies()
    {
        return Currency::where('status', 'active')->get();
    }
}
