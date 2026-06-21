<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show attribute editor for a product
    |--------------------------------------------------------------------------
    */
    public function edit($productId)
    {
        $product    = Product::with(['category', 'subcategory', 'subInCategory', 'brand', 'attributes'])->findOrFail($productId);
        $attributes = $product->attributes;

        // Separate colors, sizes, details, variations
        $colorData      = [];
        $sizeData       = [];
        $detailsData    = [];
        $variationsData = [];

        foreach ($attributes as $a) {
            $v = $a->attribute_value;
            if ($a->attribute_key === 'Color') {
                $colorData[] = [
                    'type'            => 'color',
                    'attribute_key'   => $a->attribute_key,
                    'color_name'      => $v['color_name'] ?? '',
                    'color_code'      => $v['color_code'] ?? '#000000',
                    'color_qty'       => $v['quantity'] ?? 0,
                    'existing_images' => $v['images'] ?? [],
                ];
            } elseif ($a->attribute_key === 'Size') {
                $sizeData[] = [
                    'type'          => 'size',
                    'attribute_key' => $a->attribute_key,
                    'size_value'    => $v['size_value'] ?? '',
                    'size_number'   => $v['size_number'] ?? '',
                    'size_qty'      => $v['quantity'] ?? 0,
                    'gender'        => $v['gender'] ?? '',
                    'body_parts'    => $v['body_parts'] ?? [],
                ];
            } elseif ($a->attribute_key === 'Detail') {
                $detailsData[] = [
                    'key'   => $v['key'] ?? '',
                    'value' => $v['value'] ?? '',
                ];
            } elseif ($a->attribute_key === 'Variation') {
                $variationsData[] = [
                    'variation_name'   => $v['variation_name'] ?? '',
                    'variation_price'  => $v['variation_price'] ?? '',
                    'existing_image'   => $v['image'] ?? '',
                ];
            }
        }

        // Product extra details from product columns
        $productDetails = [
            'fabric'             => $product->fabric ?? '',
            'age_group'          => $product->age_group ?? '',
            'care_instructions'  => $product->care_instructions ?? '',
            'clothing_features'  => $product->clothing_features ?? '',
            'neckline'           => $product->neckline ?? '',
            'sleeve_length_type' => $product->sleeve_length_type ?? '',
        ];

        return view('admin.product.attributes', compact(
            'product',
            'colorData',
            'sizeData',
            'detailsData',
            'variationsData',
            'productDetails'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Save / sync all attributes for a product
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // ── Server-side validation ────────────────────────────────────────
        $request->validate([
            // Product detail fields (all optional)
            'fabric'             => 'nullable|string|max:255',
            'age_group'          => 'nullable|string|max:100',
            'care_instructions'  => 'nullable|string|max:500',
            'clothing_features'  => 'nullable|string|max:500',
            'neckline'           => 'nullable|string|max:100',
            'sleeve_length_type' => 'nullable|string|max:100',

            // Details (key-value pairs)
            'details'                => 'nullable|array',
            'details.*.key'          => 'nullable|string|max:100',
            'details.*.value'        => 'nullable|string|max:500',

            // Colors
            'colors'                          => 'nullable|array',
            'colors.*.color_name'             => 'required_with:colors|string|max:100',
            'colors.*.color_code'             => 'nullable|string|max:20',
            'colors.*.color_qty'              => 'nullable|integer|min:0',
            'colors.*.color_images.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Sizes
            'sizes'               => 'nullable|array',
            'sizes.*.size_value'  => 'required_with:sizes|string|max:50',
            'sizes.*.size_number' => 'nullable|string|max:50',
            'sizes.*.size_qty'    => 'nullable|integer|min:0',
            'sizes.*.gender'      => 'nullable|string|max:50',

            // Variations
            'variations'                      => 'nullable|array',
            'variations.*.variation_name'     => 'required_with:variations|string|max:100',
            'variations.*.variation_price'    => 'nullable|numeric|min:0',
            'variations.*.variation_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'colors.*.color_name.required_with'      => 'Color name is required for each color.',
            'sizes.*.size_value.required_with'        => 'Size label is required for each size.',
            'variations.*.variation_name.required_with' => 'Variation name is required.',
            'colors.*.color_images.*.image'           => 'Color image must be a valid image.',
            'colors.*.color_images.*.max'             => 'Color image must not exceed 2MB.',
            'variations.*.variation_image.max'        => 'Variation image must not exceed 2MB.',
        ]);

        // ── 1. Save product-level detail columns ─────────────────────────
        $product->update([
            'fabric'             => $request->fabric ?? null,
            'age_group'          => $request->age_group ?? null,
            'care_instructions'  => $request->care_instructions ?? null,
            'clothing_features'  => $request->clothing_features ?? null,
            'neckline'           => $request->neckline ?? null,
            'sleeve_length_type' => $request->sleeve_length_type ?? null,
        ]);

        // ── 2. Delete all existing attributes ────────────────────────────
        $product->attributes()->delete();

        // ── 3. Save Colors ───────────────────────────────────────────────
        if ($request->has('colors')) {
            foreach ($request->colors as $index => $color) {
                $images = [];
                if ($request->hasFile("colors.{$index}.color_images")) {
                    foreach ($request->file("colors.{$index}.color_images") as $file) {
                        $name = time() . '_' . uniqid() . '.' . $file->extension();
                        $file->move(public_path('uploads/product-colors'), $name);
                        $images[] = $name;
                    }
                }
                if (empty($images) && !empty($color['existing_images'])) {
                    $images = is_array($color['existing_images'])
                        ? $color['existing_images']
                        : json_decode($color['existing_images'], true) ?? [];
                }

                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_key'   => 'Color',
                    'attribute_value' => [
                        'color_name' => $color['color_name'] ?? '',
                        'color_code' => $color['color_code'] ?? '#000000',
                        'quantity'   => (int) ($color['color_qty'] ?? 0),
                        'images'     => $images,
                    ],
                ]);
            }
        }

        // ── 4. Save Sizes ────────────────────────────────────────────────
        if ($request->has('sizes')) {
            foreach ($request->sizes as $index => $size) {
                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_key'   => 'Size',
                    'attribute_value' => [
                        'size_value'  => $size['size_value'] ?? '',
                        'size_number' => $size['size_number'] ?? '',
                        'quantity'    => (int) ($size['size_qty'] ?? 0),
                        'gender'      => $size['gender'] ?? '',
                        'body_parts'  => $size['body_parts'] ?? [],
                    ],
                ]);
            }
        }

        // ── 5. Save Product Details (key-value pairs) ────────────────────
        if ($request->has('details')) {
            foreach ($request->details as $detail) {
                if (empty($detail['key']) && empty($detail['value'])) continue;
                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_key'   => 'Detail',
                    'attribute_value' => [
                        'key'   => $detail['key'] ?? '',
                        'value' => $detail['value'] ?? '',
                    ],
                ]);
            }
        }

        // ── 6. Save Variations ───────────────────────────────────────────
        if ($request->has('variations')) {
            foreach ($request->variations as $index => $variation) {
                $image = $variation['existing_image'] ?? '';
                if ($request->hasFile("variations.{$index}.variation_image")) {
                    $file  = $request->file("variations.{$index}.variation_image");
                    $image = time() . '_' . uniqid() . '.' . $file->extension();
                    $file->move(public_path('uploads/product-variations'), $image);
                }

                ProductAttribute::create([
                    'product_id'      => $product->id,
                    'attribute_key'   => 'Variation',
                    'attribute_value' => [
                        'variation_name'  => $variation['variation_name'] ?? '',
                        'variation_price' => $variation['variation_price'] ?? '',
                        'image'           => $image,
                    ],
                ]);
            }
        }

        return redirect()->route('admin.product.attributes.edit', $product->id)
            ->with('success', 'Product attributes saved successfully.');
    }
}
