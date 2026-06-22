@csrf

<div class="space-y-6 text-[14px]">
    <!-- Section 1: Basic Information -->
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-500"></i> Basic Information
        </h3>
        <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6">
            <!-- Product Name -->
            <div class="sm:col-span-3">
                <x-input label="Product Name" name="name" :value="old('name', $product->name ?? '')" required placeholder="e.g. Core i9 Processor, Latitude 5440" />
            </div>

            <!-- Product Code (SKU) -->
            <div class="sm:col-span-3">
                <x-input label="Product Code (SKU)" name="code" :value="old('code', $product->code ?? '')" required placeholder="e.g. LPT-LAT-5440" />
                <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">Unique alphanumeric code used to identify the product SKU.</p>
            </div>

            <!-- Barcode -->
            <div class="sm:col-span-2">
                <x-input label="Barcode (ISBN/EAN)" name="barcode" :value="old('barcode', $product->barcode ?? '')" placeholder="e.g. 8901234567890" />
            </div>

            <!-- Brand -->
            <div class="sm:col-span-2">
                <x-select label="Brand" name="brand_id" required>
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <!-- Unit Name -->
            <div class="sm:col-span-2">
                <x-input label="Unit Name" name="unit_name" :value="old('unit_name', $product->unit_name ?? '')" required placeholder="e.g. Piece, Box, Kilogram" />
            </div>

            <!-- Unit Code -->
            <div class="sm:col-span-2">
                <x-input label="Unit Code" name="unit_code" :value="old('unit_code', $product->unit_code ?? '')" required placeholder="e.g. PCS, BOX, KG" />
            </div>

            <!-- Base Unit -->
            <div class="sm:col-span-2">
                <x-input label="Base Unit (Optional)" name="base_unit" :value="old('base_unit', $product->base_unit ?? '')" placeholder="e.g. unit, kg" />
            </div>

            <!-- Main Category -->
            <div class="sm:col-span-3">
                <x-select label="Main Category" name="main_category_id" id="main_category_id" required>
                    <option value="">Select Main Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('main_category_id', $product->main_category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <!-- Sub Category -->
            <div class="sm:col-span-3">
                <x-select label="Sub Category" name="sub_category_id" id="sub_category_id" required>
                    <option value="">Select Sub Category</option>
                    <!-- Populated via Javascript -->
                </x-select>
            </div>
        </div>
    </div>

    <!-- Section 2: Pricing & Inventory -->
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-hand-holding-dollar text-violet-500"></i> Pricing & Inventory
        </h3>
        <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6">
            <!-- Supplier -->
            <div class="sm:col-span-3">
                <x-select label="Supplier" name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <!-- MRP -->
            <div class="sm:col-span-3">
                <x-input label="MRP (Maximum Retail Price)" type="number" step="0.01" name="mrp" :value="old('mrp', $product->mrp ?? '0.00')" />
            </div>

            <!-- Purchase Price -->
            <div class="sm:col-span-2">
                <x-input label="Purchase Price" type="number" step="0.01" name="purchase_price" :value="old('purchase_price', $product->purchase_price ?? '0.00')" required />
            </div>

            <!-- Selling Price -->
            <div class="sm:col-span-2">
                <x-input label="Selling Price" type="number" step="0.01" name="selling_price" :value="old('selling_price', $product->selling_price ?? '0.00')" required />
            </div>

            <!-- Tax Percentage -->
            <div class="sm:col-span-1">
                <x-input label="Tax %" type="number" step="0.01" name="tax_percentage" :value="old('tax_percentage', $product->tax_percentage ?? '0.00')" />
            </div>

            <!-- Discount Percentage -->
            <div class="sm:col-span-1">
                <x-input label="Discount %" type="number" step="0.01" name="discount_percentage" :value="old('discount_percentage', $product->discount_percentage ?? '0.00')" />
            </div>

            <!-- Opening Stock -->
            <div class="sm:col-span-2">
                <x-input label="Opening Stock Quantity" type="number" step="0.01" name="opening_stock" :value="old('opening_stock', $product->stock->quantity ?? ($product->opening_stock ?? '0.00'))" />
            </div>

            <!-- Minimum Stock Alert -->
            <div class="sm:col-span-2">
                <x-input label="Minimum Stock Alert Level" type="number" step="0.01" name="minimum_stock_alert" :value="old('minimum_stock_alert', $product->minimum_stock_alert ?? '0.00')" />
            </div>

            <!-- Status -->
            <div class="sm:col-span-2">
                <x-select label="Status" name="status" required>
                    <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </x-select>
            </div>
        </div>
    </div>

    <!-- Section 3: Technical Specifications (Optional) -->
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-microchip text-emerald-500"></i> Technical Specifications
        </h3>
        <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6">
            <!-- Manufacturer -->
            <div class="sm:col-span-2">
                <x-input label="Manufacturer" name="manufacturer" :value="old('manufacturer', $product->manufacturer ?? '')" placeholder="e.g. Intel, Asus" />
            </div>

            <!-- Model Number -->
            <div class="sm:col-span-2">
                <x-input label="Model Number" name="model_number" :value="old('model_number', $product->model_number ?? '')" placeholder="e.g. ROG-STRIX-Z790" />
            </div>

            <!-- Part Number -->
            <div class="sm:col-span-2">
                <x-input label="Part / Serial Number" name="part_number" :value="old('part_number', $product->part_number ?? '')" placeholder="e.g. 90MB1CS0-M0EAY0" />
            </div>

            <!-- Warranty -->
            <div class="sm:col-span-2">
                <x-input label="Warranty Period" name="warranty" :value="old('warranty', $product->warranty ?? '')" placeholder="e.g. 3 Years, 1 Year Domestic" />
            </div>

            <!-- Color -->
            <div class="sm:col-span-2">
                <x-input label="Color" name="color" :value="old('color', $product->color ?? '')" placeholder="e.g. Space Grey, RGB" />
            </div>

            <!-- Weight -->
            <div class="sm:col-span-2">
                <x-input label="Weight (Kg/G)" name="weight" :value="old('weight', $product->weight ?? '')" placeholder="e.g. 1.2 kg, 450 g" />
            </div>

            <!-- Country of Origin -->
            <div class="sm:col-span-2">
                <x-input label="Country of Origin" name="country_of_origin" :value="old('country_of_origin', $product->country_of_origin ?? '')" placeholder="e.g. Taiwan, USA" />
            </div>

            <!-- Featured Product -->
            <div class="sm:col-span-4 flex items-center pt-6">
                <label class="relative inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300">Featured Product (Promoted on Dashboard)</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Section 4: Media Uploads & Descriptions -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-regular fa-image text-amber-500"></i> Media & Description
        </h3>
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Left Column: Images -->
            <div class="space-y-5">
                <!-- Primary Image -->
                <div class="bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-3">Product Primary Image</label>
                    <div class="flex items-center space-x-4">
                        <div class="relative shrink-0">
                            <img id="imagePreview" src="{{ (isset($product) && $product->image) ? asset('uploads/products/' . $product->image) : 'https://placehold.co/150x150/e2e8f0/94a3b8?text=No+Image' }}" alt="Product Image" class="h-24 w-24 rounded-xl object-cover border border-slate-200 dark:border-slate-800 shadow-sm">
                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <button type="button" id="triggerImageBtn" class="inline-flex items-center gap-x-1.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 px-3 py-1.5 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-750 transition-colors">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    Upload Image
                                </button>
                                <button type="button" id="removeImageBtn" class="inline-flex items-center gap-x-1.5 rounded-lg bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 px-3 py-1.5 text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-950/40 transition-colors {{ (isset($product) && $product->image) ? '' : 'hidden' }}">
                                    <i class="fa-regular fa-trash-can"></i>
                                    Remove
                                </button>
                            </div>
                            <input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">Allowed: PNG, JPG, JPEG, WEBP. Max size: 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Product Gallery -->
                <div class="bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-2">Product Gallery (Multiple Images)</label>
                    <input type="file" name="gallery[]" id="galleryInput" multiple class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-950/40 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-950/60 transition-all border border-slate-200 dark:border-slate-800 rounded-lg p-1.5 bg-white dark:bg-slate-950" accept="image/*">
                    <input type="hidden" name="clear_gallery" id="clear_gallery" value="0">
                    <input type="hidden" name="remove_gallery_images" id="remove_gallery_images" value="">
                    
                    @if(isset($product) && $product->gallery && count($product->gallery) > 0)
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Current Gallery Images (Hover & click trash to delete):</p>
                            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3" id="galleryPreviewContainer">
                                @foreach($product->gallery as $galImg)
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm" data-image="{{ $galImg }}">
                                        <img src="{{ asset('uploads/products/' . $galImg) }}" alt="Gallery Image" class="w-full h-full object-cover">
                                        <button type="button" class="remove-gallery-img-btn absolute inset-0 bg-red-650/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white focus:outline-none" title="Delete Image">
                                            <i class="fa-regular fa-trash-can text-base"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="clearAllGalleryBtn" class="mt-3 inline-flex items-center gap-x-1 text-xs font-bold text-red-600 dark:text-red-400 hover:underline">
                                <i class="fa-solid fa-circle-xmark"></i> Clear Entire Gallery
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Descriptions -->
            <div class="space-y-4">
                <div>
                    <x-textarea label="Short Description (Summary)" name="short_description" rows="3" :value="old('short_description', $product->short_description ?? '')" placeholder="Summarize key features (e.g. processor speed, core counts, RAM capacity)..." />
                </div>
                <div>
                    <x-textarea label="Full Description / Specifications" name="full_description" rows="7" :value="old('full_description', $product->full_description ?? '')" placeholder="Provide complete list of specifications, box content, and operational manual details..." />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <x-button href="{{ route('products.index') }}" variant="secondary">
        Cancel
    </x-button>
    <x-button type="submit" variant="primary">
        {{ isset($product) ? 'Update Product' : 'Save Product' }}
    </x-button>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const subCategories = @json($subCategories);
        const selectedSubCategoryId = "{{ old('sub_category_id', $product->sub_category_id ?? '') }}";

        // Category & Sub Category dropdown logic
        function loadSubcategories(mainCategoryId, preselectedId = '') {
            const subSelect = $('#sub_category_id');
            subSelect.html('<option value="">Select Sub Category</option>');
            
            const filtered = subCategories.filter(sub => sub.main_category_id == mainCategoryId);
            filtered.forEach(sub => {
                const selected = sub.id == preselectedId ? 'selected' : '';
                subSelect.append(`<option value="${sub.id}" ${selected}>${sub.name}</option>`);
            });
        }

        $('#main_category_id').on('change', function() {
            loadSubcategories($(this).val());
        });

        // Initialize with default or loaded category values
        const initialMainCatId = $('#main_category_id').val();
        if (initialMainCatId) {
            loadSubcategories(initialMainCatId, selectedSubCategoryId);
        }

        // Image upload and preview logic
        const triggerBtn = $('#triggerImageBtn');
        const removeBtn = $('#removeImageBtn');
        const fileInput = $('#imageInput');
        const preview = $('#imagePreview');
        const removeInput = $('#remove_image');

        triggerBtn.on('click', function() {
            fileInput.click();
        });

        fileInput.on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.attr('src', event.target.result);
                    removeBtn.removeClass('hidden');
                    removeInput.val('0'); // Reset removal hidden flag
                };
                reader.readAsDataURL(file);
            }
        });

        removeBtn.on('click', function() {
            preview.attr('src', 'https://placehold.co/150x150/e2e8f0/94a3b8?text=No+Image');
            fileInput.val('');
            removeBtn.addClass('hidden');
            removeInput.val('1'); // Flag to remove image on submit
        });

        // Gallery images deletion staging
        let removedGalleryImages = [];
        $('.remove-gallery-img-btn').on('click', function(e) {
            e.preventDefault();
            const container = $(this).closest('[data-image]');
            const imgName = container.data('image');

            removedGalleryImages.push(imgName);
            $('#remove_gallery_images').val(removedGalleryImages.join(','));
            container.remove();
        });

        $('#clearAllGalleryBtn').on('click', function(e) {
            e.preventDefault();
            $('#galleryPreviewContainer').empty();
            $('#clear_gallery').val('1');
            $(this).addClass('hidden');
        });
    });
</script>
@endpush
