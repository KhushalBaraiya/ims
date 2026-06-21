<?php
$base = dirname(__DIR__);
$views = $base . '/resources/views/admin';
$replacements = [
    'required: "Rating is required."' => 'required: "{{ __(\'admin.val_rating_required\') }}"',
    'required: "Category name is required"' => 'required: "{{ __(\'admin.val_category_name_required\') }}"',
    'required: "Please select an order"' => 'required: "{{ __(\'admin.val_order_required\') }}"',
    'required: "Quantity is required"' => 'required: "{{ __(\'admin.val_quantity_required\') }}"',
    'required: "Return date is required"' => 'required: "{{ __(\'admin.val_return_date_required\') }}"',
    'required: "Refund amount is required"' => 'required: "{{ __(\'admin.val_refund_amount_required\') }}"',
    'required: "Reason is required"' => 'required: "{{ __(\'admin.val_reason_required\') }}"',
    'required: "Admin note is required"' => 'required: "{{ __(\'admin.val_admin_note_required\') }}"',
    'required: "Please select return type"' => 'required: "{{ __(\'admin.val_return_type_required\') }}"',
    'required: "Please select a main category"' => 'required: "{{ __(\'admin.val_main_category_required\') }}"',
    'required: "Please select a sub category"' => 'required: "{{ __(\'admin.val_sub_category_required\') }}"',
    'required: "Name is required"' => 'required: "{{ __(\'admin.val_name_required\') }}"',
    'required: "Content is required"' => 'required: "{{ __(\'admin.val_content_required\') }}"',
    'required: "Sub category name is required"' => 'required: "{{ __(\'admin.val_subcategory_name_required\') }}"',
    'required: "Variant is required"' => 'required: "{{ __(\'admin.val_variant_required\') }}"',
    'required: "Variant type is required"' => 'required: "{{ __(\'admin.val_variant_type_required\') }}"',
    'required: "Size code is required"' => 'required: "{{ __(\'admin.val_size_code_required\') }}"',
    'required: "Please select gender"' => 'required: "{{ __(\'admin.val_gender_required\') }}"',
    'placeholder="Write a detailed answer here (e.g. The return process typically takes 5-7 business days)"' => 'placeholder="{{ __(\'admin.ph_subfaq_answer\') }}"',
    '<li class="breadcrumb-item"><a href="{{ route(\'admin.dashboard\') }}">Dashboard</a></li>' => '<li class="breadcrumb-item"><a href="{{ route(\'admin.dashboard\') }}">{{ __(\'admin.breadcrumb_dashboard\') }}</a></li>',
];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views));
$n = 0;
foreach ($it as $file) {
    if (!str_ends_with($file->getPathname(), '.blade.php')) continue;
    $c = file_get_contents($file->getPathname());
    $o = $c;
    foreach ($replacements as $from => $to) $c = str_replace($from, $to, $c);
    if ($c !== $o) { file_put_contents($file->getPathname(), $c); $n++; }
}
echo "Pass2: {$n} files\n";
