<?php
/**
 * fix_final_i18n.php — Final pass: replace all remaining hardcoded English strings
 */
$viewRoot = __DIR__ . '/resources/views';

$exact = [
    // Filter card headings
    '>Filters</h6>'                         => ">{{ __('messages.filters') }}</h6>",
    '>Filters <' => ">{{ __('messages.filters') }}<",

    // Filter form labels
    '<label class="form-label fw-semibold small">Search</label>'
        => "<label class=\"form-label fw-semibold small\">{{ __('messages.search') }}</label>",
    '<label class="form-label fw-semibold small">Status</label>'
        => "<label class=\"form-label fw-semibold small\">{{ __('messages.status') }}</label>",
    '<label class="form-label fw-semibold small">Role</label>'
        => "<label class=\"form-label fw-semibold small\">{{ __('messages.role') }}</label>",

    // Apply button text in filters
    '</i>Apply'                             => "</i>{{ __('messages.apply') }}",
    '</i>Apply '                            => "</i>{{ __('messages.apply') }} ",

    // Delete Multiples button
    '</i> Delete Multiples'                 => "</i> {{ __('messages.delete_multiples') }}",

    // All Status dropdown
    '<option value="">All Status</option>'  => "<option value=\"\">{{ __('messages.all_statuses') }}</option>",
    '<option value="">All Roles</option>'   => "<option value=\"\">{{ __('messages.all_statuses') }}</option>",

    // Swal Bulk delete - remaining hardcoded
    "confirmButtonText: 'Yes, delete all!',"  => "confirmButtonText: '{{ __(\"messages.yes_delete\") }}',",
    "title: 'Deleted!',"                       => "title: '{{ __(\"messages.deleted_title\") }}',",

    // JS translate in single-quoted strings where applicable
    "text: 'This cannot be undone.',"         => "text: '{{ __(\"messages.confirm_delete\") }}',",

    // image hint
    'JPG, PNG, GIF — max 2MB'               => "{{ __('messages.image_hint') }}",

    // Common button texts not yet translated (only those safe to replace globally)
    // Note: only in HTML context - we target specific wrappers

    // Brands publish card
    '>Publish</button>'                     => ">{{ __('messages.publish') }}</button>",

    // Products gallery/list view buttons
    '>Gallery View<'                        => ">{{ __('messages.gallery_view') }}<",
    '>List View<'                           => ">{{ __('messages.list_view') }}<",
    '>By Category<'                         => ">By Category<",  // keep as-is, no key yet

    // Stock pages
    '>Adjust Stock<'                        => ">{{ __('messages.adjust_stock') }}<",
    '>Stock History<'                       => ">{{ __('messages.stock_history') }}<",
    '>Save Adjustment<'                     => ">{{ __('messages.save_adjustment') }}<",

    // Reports
    '>Profit & Loss<'                       => ">{{ __('messages.profit_loss') }}<",
    '>Top Selling<'                         => ">{{ __('messages.top_selling') }}<",
    '>Stock Alert<'                         => ">{{ __('messages.stock_alert_menu') }}<",
    '>All Reports<'                         => ">{{ __('messages.all_reports') }}<",
    '>Sales Report<'                        => ">{{ __('messages.sales_report') }}<",
    '>Purchase Report<'                     => ">{{ __('messages.purchase_report') }}<",

    // Activity logs
    '>Activity Logs<'                       => ">{{ __('messages.activity_logs') }}<",

    // Currencies
    '>Add Currency<'                        => ">{{ __('messages.add_currency') }}<",
    '>Save Currency<'                       => ">{{ __('messages.save_currency') }}<",
    '>Edit Currency<'                       => ">{{ __('messages.edit_currency') }}<",

    // Profile
    '>Save Profile<'                        => ">{{ __('messages.save_profile') }}<",
    '>Change Password<'                     => ">{{ __('messages.change_password') }}<",

    // Settings
    '>Save Settings<'                       => ">{{ __('messages.save_settings') }}<",
];

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewRoot, RecursiveDirectoryIterator::SKIP_DOTS)
);

$fixedCount = 0;
foreach ($files as $file) {
    if ($file->getExtension() !== 'php') continue;

    $content = file_get_contents($file->getPathname());
    $original = $content;

    foreach ($exact as $find => $replace) {
        $content = str_replace($find, $replace, $content);
    }

    if ($content !== $original) {
        file_put_contents($file->getPathname(), $content);
        $fixedCount++;
        echo "Fixed: " . basename(dirname($file->getPathname())) . '/' . $file->getFilename() . "\n";
    }
}

echo "\nTotal fixed: $fixedCount\n";
