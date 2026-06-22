<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\Product;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Brands
        $brands = [
            ['name' => 'Asus', 'slug' => 'ASUS', 'status' => 'active'],
            ['name' => 'Intel', 'slug' => 'INTEL', 'status' => 'active'],
            ['name' => 'Samsung', 'slug' => 'SAMSUNG', 'status' => 'active'],
            ['name' => 'Dell', 'slug' => 'DELL', 'status' => 'active'],
            ['name' => 'Sony', 'slug' => 'SONY', 'status' => 'active'],
        ];
        foreach ($brands as $b) {
            Brand::firstOrCreate(['slug' => $b['slug']], $b);
        }

        // 2. Seed Main Categories
        $mainCats = [
            ['name' => 'Laptops & Desktops', 'slug' => 'LAPTOP-DESKTOP', 'status' => 'active'],
            ['name' => 'Computer Hardware', 'slug' => 'COMP-HARDWARE', 'status' => 'active'],
            ['name' => 'Mobile & Tablets', 'slug' => 'MOBILE-TABLET', 'status' => 'active'],
        ];
        foreach ($mainCats as $mc) {
            MainCategory::firstOrCreate(['slug' => $mc['slug']], $mc);
        }

        $laptopCat = MainCategory::where('slug', 'LAPTOP-DESKTOP')->first();
        $hardwareCat = MainCategory::where('slug', 'COMP-HARDWARE')->first();
        $mobileCat = MainCategory::where('slug', 'MOBILE-TABLET')->first();

        // 3. Seed Sub Categories
        $subCats = [
            ['main_category_id' => $laptopCat->id, 'name' => 'Laptops', 'slug' => 'LAPTOPS', 'status' => 'active', 'description' => 'Notebook computers and Ultrabooks'],
            ['main_category_id' => $hardwareCat->id, 'name' => 'Motherboards', 'slug' => 'MBOARDS', 'status' => 'active', 'description' => 'Computer system boards'],
            ['main_category_id' => $hardwareCat->id, 'name' => 'Processors (CPU)', 'slug' => 'CPUS', 'status' => 'active', 'description' => 'Central processing units'],
            ['main_category_id' => $mobileCat->id, 'name' => 'Mobile Displays', 'slug' => 'MOB-DISPLAYS', 'status' => 'active', 'description' => 'LCD and LED mobile screens'],
        ];
        foreach ($subCats as $sc) {
            SubCategory::firstOrCreate(['slug' => $sc['slug']], $sc);
        }

        // 4. Seed Currencies
        $currencies = [
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'exchange_rate' => 1.0000, 'is_default' => true, 'status' => 'active'],
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'exchange_rate' => 83.5000, 'is_default' => false, 'status' => 'active'],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 90.2000, 'is_default' => false, 'status' => 'active'],
        ];
        foreach ($currencies as $c) {
            Currency::firstOrCreate(['code' => $c['code']], $c);
        }

        // 5. Seed Suppliers
        $suppliers = [
            [
                'name' => 'Asus Tech India',
                'company_name' => 'Asus India Pvt Ltd',
                'contact_person' => 'Rajesh Kumar',
                'phone' => '+919876543210',
                'email' => 'sales@asus.in',
                'status' => 'active',
                'city' => 'Mumbai',
                'country' => 'India',
            ],
            [
                'name' => 'Intel Distributors',
                'company_name' => 'Intel Corp',
                'contact_person' => 'Sarah Connor',
                'phone' => '+14081234567',
                'email' => 'distribution@intel.com',
                'status' => 'active',
                'city' => 'Santa Clara',
                'country' => 'USA',
            ],
        ];
        foreach ($suppliers as $s) {
            Supplier::firstOrCreate(['email' => $s['email']], $s);
        }

        // 6. Seed Products
        $intelBrand = Brand::where('slug', 'INTEL')->first();
        $asusBrand = Brand::where('slug', 'ASUS')->first();
        
        $cpuSub = SubCategory::where('slug', 'CPUS')->first();
        $laptopSub = SubCategory::where('slug', 'LAPTOPS')->first();

        $intelSupplier = Supplier::where('name', 'Intel Distributors')->first();
        $asusSupplier = Supplier::where('name', 'Asus Tech India')->first();

        $products = [
            [
                'name' => 'Intel Core i7-14700K Processor',
                'slug' => 'intel-core-i7-14700k-processor',
                'code' => 'CPU-INT-I7-14700K',
                'barcode' => '735858547222',
                'brand_id' => $intelBrand->id,
                'main_category_id' => $hardwareCat->id,
                'sub_category_id' => $cpuSub->id,
                'unit_name' => 'Piece',
                'unit_code' => 'PCS',
                'base_unit' => null,
                'supplier_id' => $intelSupplier->id,
                'purchase_price' => 32000.00,
                'selling_price' => 36500.00,
                'mrp' => 42000.00,
                'tax_percentage' => 18.00,
                'discount_percentage' => 5.00,
                'opening_stock' => 25.00,
                'minimum_stock_alert' => 5.00,
                'short_description' => 'Intel Core i7-14700K 14th Gen Desktop Processor 20 Cores up to 5.6 GHz',
                'full_description' => 'Intel Core i7-14700K 14th Gen Desktop Processor 20 Cores (8 P-cores + 12 E-cores) up to 5.6 GHz LGA1700. Unlocked for overclocking, featuring Intel UHD Graphics 770.',
                'status' => 'active',
                'is_featured' => true,
                'manufacturer' => 'Intel Corporation',
                'model_number' => 'i7-14700K',
                'part_number' => 'BX8071514700K',
                'warranty' => '3 Years',
                'country_of_origin' => 'USA',
            ],
            [
                'name' => 'Asus ROG Strix G16 Gaming Laptop',
                'slug' => 'asus-rog-strix-g16-gaming-laptop',
                'code' => 'LPT-ASU-ROG-G16',
                'barcode' => '4711081942001',
                'brand_id' => $asusBrand->id,
                'main_category_id' => $laptopCat->id,
                'sub_category_id' => $laptopSub->id,
                'unit_name' => 'Piece',
                'unit_code' => 'PCS',
                'base_unit' => null,
                'supplier_id' => $asusSupplier->id,
                'purchase_price' => 110000.00,
                'selling_price' => 125000.00,
                'mrp' => 145000.00,
                'tax_percentage' => 18.00,
                'discount_percentage' => 8.00,
                'opening_stock' => 10.05,
                'minimum_stock_alert' => 2.00,
                'short_description' => 'Asus ROG Strix G16 Intel i7 13th Gen, RTX 4060, 16GB DDR5, 512GB SSD',
                'full_description' => 'Asus ROG Strix G16 (2023) Gaming Laptop. 16-inch FHD+ 165Hz Display. Intel Core i7-13650HX, NVIDIA GeForce RTX 4060 Laptop GPU, 16GB DDR5 RAM, 512GB PCIe SSD, Windows 11.',
                'status' => 'active',
                'is_featured' => true,
                'manufacturer' => 'ASUSTeK Computer Inc.',
                'model_number' => 'G614JV-AS73',
                'part_number' => '90NR0C61-M008H0',
                'warranty' => '1 Year International',
                'color' => 'Eclipse Grey',
                'weight' => '2.50 kg',
                'country_of_origin' => 'China',
            ],
        ];
        foreach ($products as $p) {
            $prod = Product::firstOrCreate(['code' => $p['code']], $p);
            
            // Create stock record
            $prod->stock()->updateOrCreate(
                ['product_id' => $prod->id],
                ['quantity' => $p['opening_stock']]
            );
        }
    }
}
