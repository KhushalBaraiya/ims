<?php

$en = include __DIR__ . '/../lang/admin/en.php';
$hi = include __DIR__ . '/../lang/admin/hi.php';
$gu = include __DIR__ . '/../lang/admin/gu.php';

$viewsPath = __DIR__ . '/../resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsPath));

$usedKeys = [];
foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $content = file_get_contents($file->getPathname());
    if (preg_match_all("/__\(['\"]admin\.([a-z0-9_]+)['\"]/", $content, $m)) {
        foreach ($m[1] as $key) {
            $usedKeys[$key] = true;
        }
    }
}

$missingInEn = array_diff_key($usedKeys, $en);
$missingHi = array_diff_key($en, $hi);
$missingGu = array_diff_key($en, $gu);
$usedNotInEn = array_diff_key($usedKeys, $en);

echo "EN keys: " . count($en) . "\n";
echo "HI keys: " . count($hi) . " (missing from EN: " . count($missingHi) . ")\n";
echo "GU keys: " . count($gu) . " (missing from EN: " . count($missingGu) . ")\n";
echo "Used in blades: " . count($usedKeys) . "\n";
echo "Used but missing in EN: " . count($usedNotInEn) . "\n";

if ($usedNotInEn) {
    echo "\n--- Keys used in views but NOT in en.php ---\n";
    foreach (array_keys($usedNotInEn) as $k) {
        echo "  $k\n";
    }
}

if ($missingHi) {
    echo "\n--- Missing in hi.php (first 30) ---\n";
    foreach (array_slice(array_keys($missingHi), 0, 30) as $k) {
        echo "  $k\n";
    }
}

if ($missingGu) {
    echo "\n--- Missing in gu.php (first 30) ---\n";
    foreach (array_slice(array_keys($missingGu), 0, 30) as $k) {
        echo "  $k\n";
    }
}
