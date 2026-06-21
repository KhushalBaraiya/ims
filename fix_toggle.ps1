$adminPath = "d:\ecom\resources\views\admin"
$files = Get-ChildItem -Path $adminPath -Recurse -Filter "*.blade.php"
$count = 0
foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw -Encoding UTF8
    # Pattern: class="form-check-input" on one line, then class="toggle-switch-input" on another line within same input tag
    if ($content -match 'class="form-check-input"' -and $content -match 'class="toggle-switch-input"') {
        # Remove the standalone class="toggle-switch-input" and add toggle-switch-input to form-check-input class
        $newContent = $content -replace 'class="form-check-input"', 'class="form-check-input toggle-switch-input"'
        $newContent = $newContent -replace '\s+class="toggle-switch-input"', ''
        Set-Content $file.FullName $newContent -Encoding UTF8 -NoNewline
        $count++
        Write-Host "Fixed: $($file.FullName)"
    }
}
Write-Host "Total files fixed: $count"
