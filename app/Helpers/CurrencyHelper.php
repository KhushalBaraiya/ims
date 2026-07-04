<?php

use App\Models\Currency;

if (!function_exists('current_currency')) {
    /**
     * Retrieve the active currency for the logged-in user.
     *
     * Priority:
     *  1. Session cache (already resolved this request)
     *  2. Logged-in user's preferred currency (user.currency column = currency code)
     *  3. Default currency in the currencies table (is_default = true)
     *  4. First currency record
     *
     * Exchange-rate convention:
     *   All amounts in the database are stored in the DEFAULT currency (INR, rate = 1).
     *   Each other currency stores how many of THAT currency equal 1 unit of the default.
     *   e.g. USD with exchange_rate = 83.5 means 1 USD = 83.5 INR
     *   So to convert INR → USD: amount_usd = amount_inr / 83.5
     */
    function current_currency(): ?Currency
    {
        $currency = session('active_currency');

        // Validate the cached value is still a Currency model instance
        if ($currency instanceof Currency) {
            return $currency;
        }

        // Try the logged-in user's preference first
        if (auth()->check()) {
            $userCode = auth()->user()->currency;
            if ($userCode) {
                $currency = Currency::where('code', strtoupper($userCode))
                    ->where('status', 'active')
                    ->first();
            }
        }

        // Fall back to the system default currency
        if (!$currency) {
            $currency = Currency::where('is_default', true)->where('status', 'active')->first();
        }

        // Last resort: first active currency
        if (!$currency) {
            $currency = Currency::where('status', 'active')->first();
        }

        if ($currency) {
            session(['active_currency' => $currency]);
        }

        return $currency;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a monetary amount according to the user's active currency.
     *
     * Amounts stored in DB are in the DEFAULT currency (exchange_rate = 1).
     * To display in another currency: converted = stored_amount / exchange_rate
     * e.g. ₹835 stored → USD: 835 / 83.5 = $10.00
     *
     * @param  float|int|string $amount  Raw amount in the default (base) currency
     * @return string  Formatted string like "₹1,234.56" or "$14.85"
     */
    function format_currency($amount): string
    {
        $currency = current_currency();
        $amount   = (float) $amount;

        if (!$currency) {
            // Absolute fallback — no currencies configured
            return '₹' . number_format($amount, 2);
        }

        $rate = (float) $currency->exchange_rate;

        // Avoid division by zero; treat rate <= 0 as 1
        $converted = ($rate > 0) ? ($amount / $rate) : $amount;

        $isNegative = $converted < 0;
        $absVal = abs($converted);

        if ($absVal < 1000) {
            $valStr = number_format($absVal, 2);
        } elseif ($absVal < 1000000) {
            $formatted = $absVal / 1000;
            $valStr = (round($formatted, 2) == (int)$formatted ? (int)$formatted : round($formatted, 2)) . 'K';
        } elseif ($absVal < 1000000000) {
            $formatted = $absVal / 1000000;
            $valStr = (round($formatted, 2) == (int)$formatted ? (int)$formatted : round($formatted, 2)) . 'M';
        } else {
            $formatted = $absVal / 1000000000;
            $valStr = (round($formatted, 2) == (int)$formatted ? (int)$formatted : round($formatted, 2)) . 'B';
        }

        return ($isNegative ? '-' : '') . $currency->symbol . $valStr;
    }
}

if (!function_exists('get_active_currencies')) {
    /**
     * Fetch all active currencies ordered by name.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_active_currencies()
    {
        return Currency::where('status', 'active')->orderBy('name')->get();
    }
}
