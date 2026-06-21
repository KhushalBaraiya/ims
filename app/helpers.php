<?php

if (!function_exists('admin_label')) {
    /**
     * Translate dynamic enum/status values for admin UI.
     *
     * @param  string|null  $value   Raw DB value (e.g. pending, percentage)
     * @param  string       $prefix  Translation key prefix (e.g. ps, pm, dt)
     */
    function admin_label(?string $value, string $prefix): string
    {
        if ($value === null || $value === '') {
            return __('admin.na');
        }

        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '_', trim($value)));
        $full = 'admin.' . $prefix . '_' . $slug;
        $translated = __($full);

        return $translated === $full ? ucfirst(str_replace('_', ' ', $slug)) : $translated;
    }
}
