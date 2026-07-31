<?php
$projectRoot = 'c:/Users/Linux/Desktop/ims';

// Define the keys to add in case they don't exist
$keys = [
    'sku_prefix' => [
        'en' => 'SKU Prefix',
        'gu' => 'SKU પ્રીફિક્સ',
        'hi' => 'एसकेयू उपसर्ग',
    ],
];

// Append keys to lang files
$langs = ['en', 'gu', 'hi'];
foreach ($langs as $lang) {
    $paths = [
        "$projectRoot/lang/$lang/messages.php",
        "$projectRoot/resources/lang/$lang/messages.php"
    ];
    
    foreach ($paths as $path) {
        if (!file_exists($path)) continue;
        
        $content = file_get_contents($path);
        
        $toAppend = "";
        foreach ($keys as $k => $vals) {
            if (strpos($content, "'$k'") === false && strpos($content, "\"$k\"") === false) {
                $val = str_replace("'", "\\'", $vals[$lang]);
                $toAppend .= "    '$k' => '$val',\n";
            }
        }
        
        if (!empty($toAppend)) {
            $pos = strrpos($content, '];');
            if ($pos !== false) {
                $content = substr_replace($content, "\n" . $toAppend . "];\n", $pos, 2);
                file_put_contents($path, $content);
                echo "Updated: $path\n";
            }
        }
    }
}

// Replacements
$replacements = [
    'resources/views/brands/publish.blade.php' => [
        '{{ old(\'status\', $brand->status ?? \'active\') == \'active\' ? \'Active\' : \'Inactive\' }}' => '{{ old(\'status\', $brand->status ?? \'active\') == \'active\' ? __(\'messages.active\') : __(\'messages.inactive\') }}',
        '{{ isset($brand) ? \'Update Brand\' : \'Save Brand\' }}' => '{{ isset($brand) ? __(\'messages.edit_brand\') : __(\'messages.add_brand\') }}',
        '<i class="bx bx-x me-1"></i> Cancel' => '<i class="bx bx-x me-1"></i> {{ __(\'messages.cancel\') }}',
        'lbl.textContent = this.checked ? \'Active\' : \'Inactive\';' => 'lbl.textContent = this.checked ? \'{{ __(\'messages.active\') }}\' : \'{{ __(\'messages.inactive\') }}\';',
    ],
    'resources/views/brands/form.blade.php' => [
        '<div class="form-text">{{ __(\'messages.description_label\') }}</div>' => '<div class="form-text">{{ __(\'messages.slug_hint\') }}</div>',
    ],
    'resources/views/main_categories/form.blade.php' => [
        '<div class="form-text">{{ __(\'messages.description_label\') }}</div>' => '<div class="form-text">{{ __(\'messages.slug_hint\') }}</div>',
    ],
    'resources/views/sub_categories/form.blade.php' => [
        '<label class="form-label fw-semibold">{{ __(\'messages.th_code\') }} / SKU Prefix <span' => '<label class="form-label fw-semibold">{{ __(\'messages.th_code\') }} / {{ __(\'messages.sku_prefix\') }} <span',
    ]
];

foreach ($replacements as $relPath => $reps) {
    $path = "$projectRoot/$relPath";
    if (!file_exists($path)) continue;
    
    $content = file_get_contents($path);
    $original = $content;
    
    foreach ($reps as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Replaced in blade: $relPath\n";
    }
}
