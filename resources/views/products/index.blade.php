@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Products Catalog</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Manage electronics inventory hardware models, serial keys, and supplier details.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <!-- Toggle Filters Button -->
            <button type="button" id="toggleFiltersBtn" class="inline-flex items-center gap-x-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-350 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-filter"></i>
                Filters
                <i id="filtersChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-405 dark:text-slate-500 ml-0.5 transition-transform duration-200"></i>
            </button>
            
            @can('products.create')
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    Add Product
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters Panel -->
    <div id="filtersCard" class="hidden mb-6">
        <x-card title="Filter Products" subtitle="Select search parameters to filter datatables row results.">
            <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6 text-[14px]">
                    <!-- Search query -->
                    <div class="sm:col-span-2">
                        <x-input label="Search Term" name="search" :value="request('search')" placeholder="SKU, Barcode, Name..." />
                    </div>

                    <!-- Brand -->
                    <div class="sm:col-span-2">
                        <x-select label="Brand" name="brand_id">
                            <option value="">All Brands</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Supplier -->
                    <div class="sm:col-span-2">
                        <x-select label="Supplier" name="supplier_id">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Category -->
                    <div class="sm:col-span-2">
                        <x-select label="Main Category" name="main_category_id" id="filter_main_category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ request('main_category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Sub Category -->
                    <div class="sm:col-span-2">
                        <x-select label="Sub Category" name="sub_category_id" id="filter_sub_category_id">
                            <option value="">All Sub Categories</option>
                            <!-- Dynamically loaded -->
                        </x-select>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <x-select label="Status" name="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-select>
                    </div>

                    <!-- Price Range -->
                    <div class="sm:col-span-3 grid grid-cols-2 gap-2">
                        <div>
                            <x-input label="Price Min" type="number" step="0.01" name="price_min" :value="request('price_min')" placeholder="Min Price" />
                        </div>
                        <div>
                            <x-input label="Price Max" type="number" step="0.01" name="price_max" :value="request('price_max')" placeholder="Max Price" />
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                        Reset Filters
                    </a>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 px-5 py-2 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                        Apply Filters
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- DataTables Card -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="productsTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4 no-sort">Image</th>
                        <th class="text-left py-3 px-4">Product Name</th>
                        <th class="text-left py-3 px-4">SKU</th>
                        <th class="text-left py-3 px-4">Brand</th>
                        <th class="text-left py-3 px-4">Category</th>
                        <th class="text-left py-3 px-4">Sub Category</th>
                        <th class="text-left py-3 px-4">Supplier</th>
                        <th class="text-left py-3 px-4">Purchase Price</th>
                        <th class="text-left py-3 px-4">Selling Price</th>
                        <th class="text-left py-3 px-4">Stock</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4 no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @foreach ($products as $index => $product)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                @if ($product->image)
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" class="h-10 w-10 rounded-lg object-cover shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                                @else
                                    <div class="h-10 w-10 bg-slate-50 dark:bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-inner">
                                        <i class="fa-regular fa-image text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">{{ $product->name }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-650 dark:text-slate-400">{{ $product->code }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-600 dark:text-slate-350">{{ $product->brand->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $product->mainCategory->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $product->subCategory->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-600 dark:text-slate-350">{{ format_currency($product->purchase_price) }}</td>
                            <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">{{ format_currency($product->selling_price) }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold {{ ($product->stock->quantity ?? 0) <= $product->minimum_stock_alert ? 'text-red-500' : 'text-emerald-500' }}">
                                    {{ number_format($product->stock->quantity ?? 0.00, 2) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <x-badge :variant="$product->status === 'active' ? 'success' : 'danger'" :text="ucfirst($product->status)" />
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('products.view')
                                        <a href="{{ route('products.show', $product->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/50 transition-colors" title="View">
                                            <i class="fa-regular fa-eye text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('products.update')
                                        <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors" title="Edit">
                                            <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('products.delete')
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-id="{{ $product->id }}" data-name="{{ $product->name }}" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors" title="Delete">
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#productsTable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            language: {
                searchPlaceholder: "Search catalog...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
        });

        // Toggle Filters Card
        const filtersCard = $('#filtersCard');
        const filtersChevron = $('#filtersChevron');
        
        // Retrieve filters open state from localStorage
        let filtersOpen = localStorage.getItem('products_filters_open') === 'true';
        if (filtersOpen) {
            filtersCard.removeClass('hidden');
            filtersChevron.addClass('rotate-180');
        }

        $('#toggleFiltersBtn').on('click', function() {
            filtersCard.slideToggle(150, function() {
                const isOpen = filtersCard.is(':visible');
                filtersChevron.toggleClass('rotate-180', isOpen);
                localStorage.setItem('products_filters_open', isOpen);
            });
        });

        // Category Dependency inside filters
        const subCategories = @json($subCategories);
        const selectedSubCategoryId = "{{ request('sub_category_id') }}";

        function loadFilterSubcategories(mainCategoryId, preselectedId = '') {
            const subSelect = $('#filter_sub_category_id');
            subSelect.html('<option value="">All Sub Categories</option>');
            
            if (!mainCategoryId) return;

            const filtered = subCategories.filter(sub => sub.main_category_id == mainCategoryId);
            filtered.forEach(sub => {
                const selected = sub.id == preselectedId ? 'selected' : '';
                subSelect.append(`<option value="${sub.id}" ${selected}>${sub.name}</option>`);
            });
        }

        $('#filter_main_category_id').on('change', function() {
            loadFilterSubcategories($(this).val());
        });

        // Init category dependency inside filters
        const initialMainCatId = $('#filter_main_category_id').val();
        if (initialMainCatId) {
            loadFilterSubcategories(initialMainCatId, selectedSubCategoryId);
        }

        // Setup SweetAlert2 delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const form = $(`#delete-form-${id}`);
            
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete product "${name}". This action will soft-delete the record.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: isDark ? '#18181b' : '#fff',
                color: isDark ? '#fff' : '#1e293b',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    background: isDark ? '#18181b' : '#fff',
                                    color: isDark ? '#fff' : '#1e293b',
                                    confirmButtonColor: '#3b82f6'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while deleting the product.');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
