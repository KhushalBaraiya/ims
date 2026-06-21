<?php
$f = dirname(__DIR__) . '/resources/views/admin/product/attributes.blade.php';
$c = file_get_contents($f);
$map = [
    'Manage colors & sizes' => "{{ __('admin.manage_colors_sizes') }}",
    'CATEGORY DETAILS' => "{{ strtoupper(__('admin.category_details')) }}",
    '>Main Category<' => ">{{ __('admin.main_category') }}<",
    '>Sub Category<' => ">{{ __('admin.sub_category') }}<",
    '>Sub In Category<' => ">{{ __('admin.sub_in_category') }}<",
    '>Brand<' => ">{{ __('admin.brand') }}<",
    '<span id="colorCount">0</span> Color' => '<span id="colorCount">0</span> {{ __(\'admin.color_count\') }}',
    '<span id="sizeCount">0</span> Size' => '<span id="sizeCount">0</span> {{ __(\'admin.size_count\') }}',
    'Add Color Attribute' => "{{ __('admin.add_color_attr') }}",
    'Add Size Attribute' => "{{ __('admin.add_size_attr') }}",
    'Attribute Rows' => "{{ __('admin.attr_rows') }}",
    'No attributes yet. Add a Color or Size using the buttons on the left.' => "{{ __('admin.attr_empty_msg') }}",
    "{{ __('admin.submit') }} {{ __('admin.description') }}" => "{{ __('admin.save_attributes') }}",
    'placeholder="Color"' => 'placeholder="{{ __(\'admin.color\') }}"',
    'placeholder="e.g. Red, Sky Blue"' => 'placeholder="{{ __(\'admin.ph_color_example\') }}"',
    'placeholder="Size"' => 'placeholder="{{ __(\'admin.size\') }}"',
    'placeholder="e.g. XL, M, 42"' => 'placeholder="{{ __(\'admin.ph_size_example\') }}"',
    'placeholder="e.g. 42"' => 'placeholder="{{ __(\'admin.ph_size_code_short\') }}"',
    '<i class="bx bx-x me-1"></i>Remove' => '<i class="bx bx-x me-1"></i>{{ __(\'admin.remove\') }}',
    '🎨 COLOR ATTRIBUTE' => '🎨 {{ __(\'admin.color_attr\') }}',
    '📐 SIZE ATTRIBUTE' => '📐 {{ __(\'admin.size_attr\') }}',
    'Attribute Key' => "{{ __('admin.attribute_key') }}",
    'Color Name' => "{{ __('admin.color_name_label') }}",
    'Color Images' => "{{ __('admin.color_images_label') }}",
    'Size Value' => "{{ __('admin.size_value_label') }}",
    'Size Number' => "{{ __('admin.size_number_label') }}",
    'Body Part' => "{{ __('admin.body_part_label') }}",
    'Quantity' => "{{ __('admin.quantity') }}",
];
foreach ($map as $from => $to) {
    $c = str_replace($from, $to, $c);
}
file_put_contents($f, $c);
echo "Fixed attributes blade\n";
