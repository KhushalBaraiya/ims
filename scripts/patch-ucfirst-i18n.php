<?php

$base = dirname(__DIR__);
$views = $base . '/resources/views';

$patterns = [
    '/\{\{\s*ucfirst\(\$ps\)\s*\}\}/' => '{{ admin_label($ps, \'ps\') }}',
    '/\{\{\s*ucfirst\(\$order->payment_status\)\s*\}\}/' => '{{ admin_label($order->payment_status, \'ps\') }}',
    '/\{\{\s*ucfirst\(\$payment->payment_status\)\s*\}\}/' => '{{ admin_label($payment->payment_status, \'ps\') }}',
    '/\{\{\s*ucfirst\(\$order->payment_method\)\s*\}\}/' => '{{ admin_label($order->payment_method, \'pm\') }}',
    '/\{\{\s*ucfirst\(\$payment->payment_method\)\s*\}\}/' => '{{ admin_label($payment->payment_method, \'pm\') }}',
    '/\{\{\s*ucfirst\(\$order->status\s*\?\?\s*__\(\'admin\.na\'\)\)\s*\}\}/' => '{{ ($order->status ?? \'\') === \'active\' ? __(\'admin.active\') : (($order->status ?? \'\') === \'inactive\' ? __(\'admin.inactive\') : admin_label($order->status ?? \'\', \'ps\')) }}',
    '/\{\{\s*ucfirst\(\$order->status\)\s*\}\}/' => '{{ admin_label($order->status, \'ps\') }}',
    '/\{\{\s*ucfirst\(\$return->return_type\)\s*\}\}/' => '{{ admin_label($return->return_type, \'rt\') }}',
    '/\{\{\s*ucfirst\(\$return->status\)\s*\}\}/' => '{{ admin_label($return->status, \'ps\') }}',
    '/\{\{\s*ucfirst\(\$coupon->discount_type\)\s*\}\}/' => '{{ admin_label($coupon->discount_type, \'dt\') }}',
    '/\{\{\s*ucfirst\(\$offer->offer_type\)\s*\}\}/' => '{{ admin_label($offer->offer_type, \'dt\') }}',
    '/\{\{\s*ucfirst\(\$address->address_type\)\s*\}\}/' => '{{ admin_label($address->address_type, \'at\') }}',
    '/\{\{\s*ucfirst\(\$notif->type\)\s*\}\}/' => '{{ admin_label($notif->type, \'nt\') }}',
    '/\{\{\s*ucfirst\(\$demo->gender\)\s*\}\}/' => '{{ admin_label($demo->gender, \'gender\') }}',
    '/\{\{\s*ucfirst\(\$payment->gateway\s*\?\?\s*__\(\'admin\.dash\'\)\)\s*\}\}/' => '{{ admin_label($payment->gateway ?? \'\', \'pm\') }}',
];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views));
$patched = 0;
foreach ($iterator as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }
    $path = $file->getPathname();
    $content = file_get_contents($path);
    $original = $content;
    foreach ($patterns as $regex => $replacement) {
        $content = preg_replace($regex, $replacement, $content);
    }
    if ($content !== $original) {
        file_put_contents($path, $content);
        $patched++;
        echo str_replace($base . DIRECTORY_SEPARATOR, '', $path) . PHP_EOL;
    }
}

echo "Patched {$patched} files.\n";
