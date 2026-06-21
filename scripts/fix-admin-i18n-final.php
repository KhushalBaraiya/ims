<?php
$base = dirname(__DIR__);
$admin = $base . '/resources/views/admin';

$replacements = [
    // Status toggle bug: DB values are 'active'/'inactive', not translated labels
    "?? __('admin.active')) == __('admin.active')" => "?? 'active') == 'active'",
    "?? __('admin.active')) == __('admin.active') ? 'checked'" => "?? 'active') == 'active' ? 'checked'",
    "?? __('admin.active')) == __('admin.active') ? 'text-success'" => "?? 'active') == 'active' ? 'text-success'",
    "?? __('admin.active')) == __('admin.active') ? __('admin.status_active')" => "?? 'active') == 'active' ? __('admin.status_active')",

    // Select placeholders
    '<option value="">-- Select User --</option>' => '<option value="">{{ __(\'admin.select_user\') }}</option>',
    '<option value="">-- Select Product --</option>' => '<option value="">{{ __(\'admin.select_product\') }}</option>',
    '<option value="">-- Select Order --</option>' => '<option value="">{{ __(\'admin.select_order\') }}</option>',
    '<option value="">-- Select Category --</option>' => '<option value="">{{ __(\'admin.select_category\') }}</option>',
    '<option value="">-- Select Sub Category --</option>' => '<option value="">{{ __(\'admin.select_sub_category\') }}</option>',
    '<option value="">-- Select Brand --</option>' => '<option value="">{{ __(\'admin.select_brand\') }}</option>',
    '<option value="">-- Select Gender --</option>' => '<option value="">{{ __(\'admin.select_gender\') }}</option>',
    '<option value="">-- Select --</option>' => '<option value="">{{ __(\'admin.select_option\') }}</option>',

    // Validation leftovers
    'minlength: "Min 10 characters"' => 'minlength: "{{ __(\'admin.val_min_10\') }}"',
    'max: "Discount cannot exceed 100%."' => 'max: "{{ __(\'admin.val_discount_max\') }}"',
    'filesize: "Each image must not exceed 2MB."' => 'filesize: "{{ __(\'admin.val_image_size\') }}"',
    'number: "Enter valid amount"' => 'number: "{{ __(\'admin.val_number_valid\') }}"',
    'min: "Minimum 1 required"' => 'min: "{{ __(\'admin.val_min_1\') }}"',
    'date: "Enter valid date"' => 'date: "{{ __(\'admin.val_date_valid\') }}"',
    "date: 'Enter valid date'" => "date: '{{ __(\'admin.val_date_valid\') }}'",
    "number: 'Enter valid price'" => "number: '{{ __(\'admin.val_price_number\') }}'",
    "number: 'Enter valid total'" => "number: '{{ __(\'admin.val_total_price_number\') }}'",
    "number: 'Enter valid amount'" => "number: '{{ __(\'admin.val_number_valid\') }}'",

    // Labels
    '<label class="form-label fw-semibold d-block">Status</label>' => '<label class="form-label fw-semibold d-block">{{ __(\'admin.status\') }}</label>',
    '<label class="form-label fw-semibold d-block">Status</label>' => '<label class="form-label fw-semibold d-block">{{ __(\'admin.status\') }}</label>',

    // Product create/show
    'Update Product' => "{{ __('admin.update_product') }}",
    'Save Product' => "{{ __('admin.save_product') }}",
    'class="btn btn-outline-secondary btn-lg">
                                Cancel
                            </a>' => 'class="btn btn-outline-secondary btn-lg">
                                {{ __(\'admin.cancel\') }}
                            </a>',
    '<i class="bx bx-category me-2 text-warning"></i>Classification' => '<i class="bx bx-category me-2 text-warning"></i>{{ __(\'admin.classification\') }}',
    '<i class="bx bx-images me-2 text-info"></i>Product Images' => '<i class="bx bx-images me-2 text-info"></i>{{ __(\'admin.product_images\') }}',
    'Click or drag images here' => "{{ __('admin.click_drag_images') }}",
    'JPG, PNG, WEBP — max 2MB each' => "{{ __('admin.jpg_png_webp_each') }}",
    '<div class="text-muted small">Customer saves</div>' => '<div class="text-muted small">{{ __(\'admin.customer_saves\') }}</div>',
    '🤍 No' => '🤍 {{ __(\'admin.no\') }}',
    '❤️ Yes' => '❤️ {{ __(\'admin.yes\') }}',

    // Product show
    '<span class="text-muted fw-semibold">Favourite</span>' => '<span class="text-muted fw-semibold">{{ __(\'admin.is_favourite\') }}</span>',
    '<span class="text-muted fw-semibold">Rating</span>' => '<span class="text-muted fw-semibold">{{ __(\'admin.rating\') }}</span>',
    ' photos</span>' => ' {{ __(\'admin.photos\') }}</span>',
    '<i class="bx bxs-heart"></i> Yes</span>' => '<i class="bx bxs-heart"></i> {{ __(\'admin.yes\') }}</span>',
    '<i class="bx bx-heart"></i> No</span>' => '<i class="bx bx-heart"></i> {{ __(\'admin.no\') }}</span>',
    '<div class="text-muted small mb-1">Selling Price</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.selling_price\') }}</div>',
    '<div class="text-muted small mb-1">MRP</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.mrp_label\') }}</div>',
    '<div class="text-muted small mb-1">Discount</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.discount\') }}</div>',
    '<div class="text-muted small mb-1">Total Price</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.total\') }}</div>',
    '<div class="text-muted small mb-1">Stock Qty</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.quantity\') }}</div>',
    '<i class="bx bx-rupee me-1"></i>Pricing' => '<i class="bx bx-rupee me-1"></i>{{ __(\'admin.pricing\') }}',
    '<div class="text-muted small mb-1">Category</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.category\') }}</div>',
    '<div class="text-muted small mb-1">Sub Category</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.sub_category\') }}</div>',
    '<div class="text-muted small mb-1">Sub In Category</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.sub_in_category\') }}</div>',
    '<div class="text-muted small mb-1">Brand</div>' => '<div class="text-muted small mb-1">{{ __(\'admin.brand\') }}</div>',
    '<i class="bx bx-palette me-2 text-primary"></i>Colors' => '<i class="bx bx-palette me-2 text-primary"></i>{{ __(\'admin.color\') }}',

    // Demo
    '<label class="form-label">Name</label>' => '<label class="form-label">{{ __(\'admin.name\') }}</label>',
    '<label class="form-label">Email</label>' => '<label class="form-label">{{ __(\'admin.email\') }}</label>',
    '<label class="form-label">Phone</label>' => '<label class="form-label">{{ __(\'admin.phone\') }}</label>',
    '<label class="form-label">Image</label>' => '<label class="form-label">{{ __(\'admin.image\') }}</label>',
    '<label class="form-label">Gender</label>' => '<label class="form-label">{{ __(\'admin.gender\') }}</label>',
    '<label class="form-label">Address</label>' => '<label class="form-label">{{ __(\'admin.address\') }}</label>',
    '<label class="form-label">Status</label>' => '<label class="form-label">{{ __(\'admin.status\') }}</label>',
    '<th>ID</th>' => '<th>{{ __(\'admin.id\') }}</th>',
    '<th>Image</th>' => '<th>{{ __(\'admin.image\') }}</th>',
    '<th>Name</th>' => '<th>{{ __(\'admin.name\') }}</th>',
    '<th>Email</th>' => '<th>{{ __(\'admin.email\') }}</th>',
    '<th>Phone</th>' => '<th>{{ __(\'admin.phone\') }}</th>',
    '<th>Gender</th>' => '<th>{{ __(\'admin.gender\') }}</th>',
    '<th>Status</th>' => '<th>{{ __(\'admin.status\') }}</th>',
    'Select Gender' => "{{ __('admin.select_gender') }}",
    '>Back<' => ">{{ __('admin.back') }}<",
];


$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($admin));
$n = 0;
foreach ($it as $file) {
    if (!str_ends_with($file->getPathname(), '.blade.php')) continue;
    $c = file_get_contents($file->getPathname());
    $o = $c;
    foreach ($replacements as $from => $to) {
        $c = str_replace($from, $to, $c);
    }
    if ($c !== $o) {
        file_put_contents($file->getPathname(), $c);
        $n++;
        echo basename(dirname($file->getPathname())) . '/' . basename($file->getPathname()) . PHP_EOL;
    }
}

echo "Updated {$n} files\n";
