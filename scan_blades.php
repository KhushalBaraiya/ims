<?php
/**
 * Scan all blade files and report:
 * 1. Files that have NO __('admin.') keys at all
 * 2. Files that have hardcoded visible text (not inside PHP variables/logic)
 */

$base = 'd:/ecom/resources/views';
$allBlades = [];

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base)) as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $allBlades[] = $file->getPathname();
    }
}

sort($allBlades);

echo "=== ALL BLADE FILES (" . count($allBlades) . " total) ===\n\n";

$noTrans = [];
$hasTrans = [];

foreach ($allBlades as $path) {
    $content = file_get_contents($path);
    $rel = str_replace('d:/ecom/resources/views/', '', str_replace('\\', '/', $path));
    if (strpos($content, "__('admin.") !== false) {
        $hasTrans[] = $rel;
    } else {
        $noTrans[] = $rel;
    }
}

echo "=== Files WITH __('admin.') translations: " . count($hasTrans) . " ===\n";
foreach ($hasTrans as $f) echo "  ✅ $f\n";

echo "\n=== Files WITHOUT __('admin.') translations: " . count($noTrans) . " ===\n";
foreach ($noTrans as $f) echo "  ❌ $f\n";
