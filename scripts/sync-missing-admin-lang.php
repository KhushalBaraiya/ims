<?php

/**
 * Copy any keys from lang/admin/en.php missing in hi.php and gu.php (values from en as fallback).
 * Run: php scripts/sync-missing-admin-lang.php
 */

$base = dirname(__DIR__);
$en = include $base . '/lang/admin/en.php';

foreach (['hi', 'gu'] as $locale) {
    $path = $base . "/lang/admin/{$locale}.php";
    $data = include $path;
    $added = 0;
    foreach ($en as $key => $value) {
        if (!array_key_exists($key, $data)) {
            $data[$key] = $value;
            $added++;
        }
    }
    if ($added > 0) {
        $export = var_export($data, true);
        file_put_contents($path, "<?php\n\nreturn " . $export . ";\n");
        echo "{$locale}: added {$added} keys\n";
    } else {
        echo "{$locale}: up to date\n";
    }
}
