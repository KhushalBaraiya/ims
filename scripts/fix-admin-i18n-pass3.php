<?php
$base = dirname(__DIR__) . '/resources/views/admin';

$map = [
    "required: 'Please select a user'" => "required: '{{ __(\"admin.val_user_required\") }}'",
    "required: 'Please select a user.'" => "required: '{{ __(\"admin.val_user_required\") }}'",
    "required: 'Please select a product'" => "required: '{{ __(\"admin.val_product_required\") }}'",
    "required: 'Order number is required'" => "required: '{{ __(\"admin.val_order_no_required\") }}'",
    "required: 'Order date is required'" => "required: '{{ __(\"admin.val_order_date_required\") }}'",
    "required: 'Quantity is required'" => "required: '{{ __(\"admin.val_quantity_required\") }}'",
    "required: 'Price is required'" => "required: '{{ __(\"admin.val_price_required\") }}'",
    "required: 'Total amount is required'" => "required: '{{ __(\"admin.val_total_amount_required\") }}'",
    "required: 'Please select a payment method'" => "required: '{{ __(\"admin.val_payment_method_required\") }}'",
    "required: 'Please select a payment status'" => "required: '{{ __(\"admin.val_payment_status_required\") }}'",
    "required: 'Shipping address is required'" => "required: '{{ __(\"admin.val_shipping_address_required\") }}'",
    "required: 'Please select a role.'" => "required: '{{ __(\"admin.val_role_required\") }}'",
    "required: 'Name is required.'" => "required: '{{ __(\"admin.val_name_required\") }}'",
    "minlength: 'Name must be at least 2 characters.'" => "minlength: '{{ __(\"admin.val_min_2\") }}'",
    "required: 'Email address is required.'" => "required: '{{ __(\"admin.val_email_required\") }}'",
    "required: 'Phone number is required.'" => "required: '{{ __(\"admin.val_phone_required\") }}'",
    "minlength: 'Phone number must be at least 7 digits.'" => "minlength: '{{ __(\"admin.val_phone_min\") }}'",
    "required: 'Please select a gender.'" => "required: '{{ __(\"admin.val_gender_required\") }}'",
    "required: 'OTP is required.'" => "required: '{{ __(\"admin.val_otp_required\") }}'",
    "minlength: 'OTP must be exactly 6 digits.'" => "minlength: '{{ __(\"admin.val_otp_digits\") }}'",
    "required: 'Address is required.'" => "required: '{{ __(\"admin.val_address_required\") }}'",
    "minlength: 'Address must be at least 5 characters.'" => "minlength: '{{ __(\"admin.val_min_5\") }}'",
    "required: 'Password is required.'" => "required: '{{ __(\"admin.val_password_required\") }}'",
    "minlength: 'Password must be at least 8 characters.'" => "minlength: '{{ __(\"admin.min_8_chars\") }}'",
    "required: 'Profile picture is required.'" => "required: '{{ __(\"admin.val_profile_image_required\") }}'",
    "required: 'Image is required'" => "required: '{{ __(\"admin.val_image_required\") }}'",
    "required: 'First name is required.'" => "required: '{{ __(\"admin.val_first_name_required\") }}'",
    "minlength: 'First name must be at least 2 characters.'" => "minlength: '{{ __(\"admin.val_min_2\") }}'",
    "maxlength: 'First name cannot exceed 100 characters.'" => "maxlength: '{{ __(\"admin.val_max_100\") }}'",
    "required: 'Last name is required.'" => "required: '{{ __(\"admin.val_last_name_required\") }}'",
    "required: 'Mobile number is required.'" => "required: '{{ __(\"admin.val_mobile_required\") }}'",
    "digits: 'Mobile number must contain digits only.'" => "digits: '{{ __(\"admin.val_mobile_digits\") }}'",
    "minlength: 'Mobile number must be at least 7 digits.'" => "minlength: '{{ __(\"admin.val_phone_min\") }}'",
    "maxlength: 'Mobile number cannot exceed 15 digits.'" => "maxlength: '{{ __(\"admin.val_mobile_max\") }}'",
    "email: 'Please enter a valid email address.'" => "email: '{{ __(\"admin.val_email_valid\") }}'",
    "required: 'House number is required.'" => "required: '{{ __(\"admin.val_house_required\") }}'",
    "}, 'Invalid number.');" => "}, '{{ __(\"admin.val_number_valid\") }}');",
    "}, 'Invalid format.');" => "}, '{{ __(\"admin.val_invalid_format\") }}');",
    "if (!confirm('Delete this image?'))" => "if (!confirm('{{ __(\"admin.confirm_delete_image\") }}'))",
    "onclick=\"return confirm('Are you sure?')\"" => "onclick=\"return confirm('{{ __(\"admin.swal_are_you_sure\") }}')\"",
    '<i class="bx bx-detail me-2 text-primary"></i>Basic Information' => '<i class="bx bx-detail me-2 text-primary"></i>{{ __(\"admin.basic_information\") }}',
    '<i class="bx bx-revision me-2 text-primary"></i>Return Order Details' => '<i class="bx bx-revision me-2 text-primary"></i>{{ __(\"admin.return_order_details\") }}',
    "{{ isset($demo) ? 'Update' : 'Save' }}" => "{{ isset($demo) ? __('admin.update') : __('admin.save') }}",
];

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
$n = 0;
foreach ($it as $file) {
    if (!str_ends_with($file->getPathname(), '.blade.php')) continue;
    $c = file_get_contents($file->getPathname());
    $o = $c;
    foreach ($map as $from => $to) $c = str_replace($from, $to, $c);
    if ($c !== $o) { file_put_contents($file->getPathname(), $c); $n++; }
}
echo "Pass3: {$n} files\n";
