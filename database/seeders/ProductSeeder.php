<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * ProductSeeder — covers every gallery / stock test scenario:
 *
 *  ✅ Normal stock (well above alert)
 *  ⚠️  Low stock  (qty <= minimum_stock_alert, qty > 0)
 *  ❌  Out of stock (qty = 0)
 *  🔴  Inactive product
 *  🖼️  No image product
 *  💰  High-value product
 *  🏷️  Zero-profit / loss product
 *  🔢  High stock (accessories)
 *  🌐  Multiple categories & brands
 */
class ProductSeeder extends Seeder
{
    /* ── helpers ── */
    private function brand(string $slug): int
    {
        return Brand::where('slug', Str::upper($slug))->value('id') ?? 0;
    }
    private function mainCat(string $slug): int
    {
        return MainCategory::where('slug', $slug)->value('id') ?? 0;
    }
    private function subCat(string $slug): int
    {
        return SubCategory::where('slug', $slug)->value('id') ?? 0;
    }

    public function run(): void
    {
        $this->seedProducts();
    }

    private function make(array $d): array
    {
        return array_merge([
            'tax_percentage'      => 18,
            'unit_name'           => 'Piece',
            'unit_code'           => 'PCS',
            'warranty'            => '6 Months',
            'color'               => 'N/A',
            'weight'              => '200g',
            'country_of_origin'   => 'India',
            'image'               => null,
            'gallery'             => [],
            'status'              => 'active',
        ], $d);
    }

    private function seedProducts(): void
    {
        $products = [];

        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 1 — NORMAL STOCK  (qty well above alert)
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Samsung Galaxy A53 Motherboard',
            'slug' => 'samsung-galaxy-a53-motherboard',
            'code' => 'MMB-SAM-A53',
            'barcode' => 'MMB-SAM-A53',
            'brand_id' => $this->brand('Samsung'),
            'main_category_id' => $this->mainCat('MOBILE-MB'),
            'sub_category_id' => $this->subCat('SAMSUNG-MOBILE-MB'),
            'purchase_price' => 3200, 'selling_price' => 4800,
            'minimum_stock_alert' => 5, 'stock_quantity' => 25,
            'image' => 'seed_mobile_mb.jpg',
            'short_description' => 'Original replacement motherboard for Samsung Galaxy A53 5G.',
            'full_description'  => 'Genuine OEM logic board for Samsung Galaxy A53 5G. Fully tested.',
            'manufacturer' => 'Samsung', 'model_number' => 'Galaxy A53',
            'part_number' => 'MMB-SAM-A53', 'country_of_origin' => 'South Korea',
        ]);

        $products[] = $this->make([
            'name' => 'Dell Inspiron 15 Motherboard',
            'slug' => 'dell-inspiron-15-motherboard',
            'code' => 'MB-DEL-INS15',
            'barcode' => 'MB-DEL-INS15',
            'brand_id' => $this->brand('Dell'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('DELL-MB'),
            'purchase_price' => 5000, 'selling_price' => 7200,
            'minimum_stock_alert' => 3, 'stock_quantity' => 10,
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'Replacement motherboard for Dell Inspiron 15 3000/5000 series.',
            'full_description'  => 'Genuine Dell OEM motherboard for Inspiron 15. Plug-and-play replacement.',
            'manufacturer' => 'Dell', 'model_number' => 'Inspiron 15',
            'part_number' => 'MB-DEL-INS15', 'weight' => '380g',
        ]);

        $products[] = $this->make([
            'name' => 'HP Pavilion 14 Motherboard',
            'slug' => 'hp-pavilion-14-motherboard',
            'code' => 'MB-HP-PAV14',
            'barcode' => 'MB-HP-PAV14',
            'brand_id' => $this->brand('HP'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('HP-MB'),
            'purchase_price' => 4200, 'selling_price' => 6000,
            'minimum_stock_alert' => 3, 'stock_quantity' => 11,
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'OEM motherboard compatible with HP Pavilion 14 series.',
            'manufacturer' => 'HP', 'model_number' => 'Pavilion 14',
            'part_number' => 'MB-HP-PAV14', 'weight' => '340g',
        ]);

        $products[] = $this->make([
            'name' => 'Xiaomi Redmi Note 12 Motherboard',
            'slug' => 'xiaomi-redmi-note-12-motherboard',
            'code' => 'MMB-XIA-RN12',
            'barcode' => 'MMB-XIA-RN12',
            'brand_id' => $this->brand('Xiaomi'),
            'main_category_id' => $this->mainCat('MOBILE-MB'),
            'sub_category_id' => $this->subCat('XIAOMI-MOBILE-MB'),
            'purchase_price' => 2800, 'selling_price' => 4200,
            'minimum_stock_alert' => 5, 'stock_quantity' => 30,
            'image' => 'seed_mobile_mb.jpg',
            'short_description' => 'Compatible OEM motherboard for Xiaomi Redmi Note 12 series.',
            'manufacturer' => 'Xiaomi', 'model_number' => 'Redmi Note 12',
            'part_number' => 'MMB-XIA-RN12', 'weight' => '110g',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 2 — LOW STOCK  (qty <= minimum_stock_alert, qty > 0)
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Asus TUF F15 Motherboard ⚠️ LOW',
            'slug' => 'asus-tuf-f15-motherboard',
            'code' => 'MB-ASU-TUFF15',
            'barcode' => 'MB-ASU-TUFF15',
            'brand_id' => $this->brand('Asus'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('ASUS-MB'),
            'purchase_price' => 7500, 'selling_price' => 10500,
            'minimum_stock_alert' => 5,
            'stock_quantity' => 2,          // ← qty 2 ≤ alert 5  → LOW STOCK
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'High-performance OEM motherboard for Asus TUF Gaming F15.',
            'manufacturer' => 'Asus', 'model_number' => 'TUF F15',
            'part_number' => 'MB-ASU-TUFF15', 'weight' => '400g',
        ]);

        $products[] = $this->make([
            'name' => 'iPhone 13 OLED Display ⚠️ LOW',
            'slug' => 'iphone-13-oled-display',
            'code' => 'DSP-APL-IP13',
            'barcode' => 'DSP-APL-IP13',
            'brand_id' => $this->brand('Apple'),
            'main_category_id' => $this->mainCat('DISPLAY'),
            'sub_category_id' => $this->subCat('MOBILE-DISPLAY'),
            'purchase_price' => 5500, 'selling_price' => 8500,
            'minimum_stock_alert' => 3,
            'stock_quantity' => 1,          // ← qty 1 ≤ alert 3  → LOW STOCK
            'image' => 'seed_display_mobile.jpg',
            'short_description' => 'OLED display assembly for Apple iPhone 13.',
            'manufacturer' => 'Apple', 'model_number' => 'iPhone 13',
            'part_number' => 'DSP-APL-IP13', 'weight' => '80g',
            'warranty' => '3 Months', 'country_of_origin' => 'China',
        ]);

        $products[] = $this->make([
            'name' => 'Lenovo IdeaPad 3 Motherboard ⚠️ LOW',
            'slug' => 'lenovo-ideapad-3-motherboard',
            'code' => 'MB-LEN-IP3',
            'barcode' => 'MB-LEN-IP3',
            'brand_id' => $this->brand('Lenovo'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('LENOVO-MB'),
            'purchase_price' => 4800, 'selling_price' => 6800,
            'minimum_stock_alert' => 4,
            'stock_quantity' => 3,          // ← qty 3 ≤ alert 4  → LOW STOCK
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'Compatible OEM motherboard for Lenovo IdeaPad 3 series.',
            'manufacturer' => 'Lenovo', 'model_number' => 'IdeaPad 3',
            'part_number' => 'MB-LEN-IP3', 'weight' => '360g',
        ]);

        $products[] = $this->make([
            'name' => 'Intel Core i5-12400 Processor ⚠️ LOW',
            'slug' => 'intel-core-i5-12400-processor',
            'code' => 'CPU-INT-I512400',
            'barcode' => 'CPU-INT-I512400',
            'brand_id' => $this->brand('Intel'),
            'main_category_id' => $this->mainCat('ELECTRONICE-ITEM'),
            'sub_category_id' => $this->subCat('CPUS'),
            'purchase_price' => 13500, 'selling_price' => 16500,
            'minimum_stock_alert' => 3,
            'stock_quantity' => 2,          // ← LOW STOCK
            'image' => 'seed_ram.jpg',
            'short_description' => 'Intel Core i5-12400 6-core 12-thread LGA1700 processor.',
            'manufacturer' => 'Intel', 'model_number' => 'Core i5-12400',
            'part_number' => 'BX8071512400', 'warranty' => '3 Years',
            'weight' => '75g', 'country_of_origin' => 'Malaysia',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 3 — OUT OF STOCK  (qty = 0)
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'OnePlus Nord CE3 Motherboard ❌ OUT',
            'slug' => 'oneplus-nord-ce3-motherboard',
            'code' => 'MMB-OP-NCE3',
            'barcode' => 'MMB-OP-NCE3',
            'brand_id' => $this->brand('OnePlus'),
            'main_category_id' => $this->mainCat('MOBILE-MB'),
            'sub_category_id' => $this->subCat('ONEPLUS-MOBILE-MB'),
            'purchase_price' => 3500, 'selling_price' => 5200,
            'minimum_stock_alert' => 5,
            'stock_quantity' => 0,          // ← OUT OF STOCK
            'image' => 'seed_mobile_mb.jpg',
            'short_description' => 'Genuine OEM logic board for OnePlus Nord CE3.',
            'manufacturer' => 'OnePlus', 'model_number' => 'Nord CE3',
            'part_number' => 'MMB-OP-NCE3', 'weight' => '115g',
            'warranty' => '3 Months',
        ]);

        $products[] = $this->make([
            'name' => 'AMD Radeon RX 6600 GPU ❌ OUT',
            'slug' => 'amd-radeon-rx-6600-gpu',
            'code' => 'GPU-AMD-RX6600',
            'barcode' => 'GPU-AMD-RX6600',
            'brand_id' => $this->brand('AMD'),
            'main_category_id' => $this->mainCat('ELECTRONICE-ITEM'),
            'sub_category_id' => $this->subCat('GPU'),
            'purchase_price' => 18000, 'selling_price' => 22500,
            'minimum_stock_alert' => 2,
            'stock_quantity' => 0,          // ← OUT OF STOCK
            'image' => 'seed_gpu.jpg',
            'short_description' => 'AMD Radeon RX 6600 8GB GDDR6, PCIe 4.0.',
            'manufacturer' => 'AMD', 'model_number' => 'RX 6600',
            'part_number' => 'GPU-AMD-RX6600', 'warranty' => '3 Years',
            'weight' => '700g', 'country_of_origin' => 'USA',
        ]);

        $products[] = $this->make([
            'name' => 'Samsung A53 AMOLED Display ❌ OUT',
            'slug' => 'samsung-a53-amoled-display',
            'code' => 'DSP-SAM-A53',
            'barcode' => 'DSP-SAM-A53',
            'brand_id' => $this->brand('Samsung'),
            'main_category_id' => $this->mainCat('DISPLAY'),
            'sub_category_id' => $this->subCat('MOBILE-DISPLAY'),
            'purchase_price' => 2800, 'selling_price' => 4200,
            'minimum_stock_alert' => 5,
            'stock_quantity' => 0,          // ← OUT OF STOCK
            'image' => 'seed_display_mobile.jpg',
            'short_description' => 'Original AMOLED display with touch digitizer for Samsung Galaxy A53 5G.',
            'manufacturer' => 'Samsung', 'model_number' => 'Galaxy A53',
            'part_number' => 'DSP-SAM-A53', 'weight' => '90g',
            'warranty' => '3 Months', 'country_of_origin' => 'South Korea',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 4 — INACTIVE PRODUCT
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Realme 11 Pro Motherboard 🔴 INACTIVE',
            'slug' => 'realme-11-pro-motherboard',
            'code' => 'MMB-REA-11P',
            'barcode' => 'MMB-REA-11P',
            'brand_id' => $this->brand('Realme'),
            'main_category_id' => $this->mainCat('MOBILE-MB'),
            'sub_category_id' => $this->subCat('REALME-MOBILE-MB'),
            'purchase_price' => 2500, 'selling_price' => 3800,
            'minimum_stock_alert' => 5, 'stock_quantity' => 12,
            'status' => 'inactive',         // ← INACTIVE
            'image' => 'seed_mobile_mb.jpg',
            'short_description' => 'Compatible OEM motherboard for Realme 11 Pro.',
            'manufacturer' => 'Realme', 'model_number' => 'Realme 11 Pro',
            'part_number' => 'MMB-REA-11P', 'weight' => '108g', 'warranty' => '3 Months',
        ]);

        $products[] = $this->make([
            'name' => 'Oppo A78 Motherboard 🔴 INACTIVE',
            'slug' => 'oppo-a78-motherboard',
            'code' => 'MMB-OPP-A78',
            'barcode' => 'MMB-OPP-A78',
            'brand_id' => $this->brand('Oppo'),
            'main_category_id' => $this->mainCat('MOBILE-MB'),
            'sub_category_id' => $this->subCat('OPPO-MOBILE-MB'),
            'purchase_price' => 2200, 'selling_price' => 3500,
            'minimum_stock_alert' => 5, 'stock_quantity' => 8,
            'status' => 'inactive',         // ← INACTIVE
            'image' => 'seed_mobile_mb.jpg',
            'short_description' => 'Compatible OEM logic board for Oppo A78.',
            'manufacturer' => 'Oppo', 'model_number' => 'Oppo A78',
            'part_number' => 'MMB-OPP-A78', 'weight' => '105g', 'warranty' => '3 Months',
        ]);

        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 5 — NO IMAGE  (image = null)
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Generic USB Hub 4-Port 🖼️ NO IMG',
            'slug' => 'generic-usb-hub-4-port',
            'code' => 'ACC-HUB-USB4',
            'barcode' => 'ACC-HUB-USB4',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('USB-CABLES'),
            'purchase_price' => 150, 'selling_price' => 299,
            'minimum_stock_alert' => 10, 'stock_quantity' => 45,
            'image' => null,                // ← NO IMAGE
            'short_description' => 'USB 3.0 4-port hub with data transfer up to 5Gbps.',
            'manufacturer' => 'Generic', 'model_number' => 'USB4-HUB',
            'part_number' => 'ACC-HUB-USB4', 'weight' => '80g', 'warranty' => '1 Month',
        ]);

        $products[] = $this->make([
            'name' => 'Dell Laptop Charger 65W 🖼️ NO IMG',
            'slug' => 'dell-laptop-charger-65w',
            'code' => 'ACC-CHR-DEL65',
            'barcode' => 'ACC-CHR-DEL65',
            'brand_id' => $this->brand('Dell'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('USB-CABLES'),
            'purchase_price' => 600, 'selling_price' => 999,
            'minimum_stock_alert' => 8, 'stock_quantity' => 22,
            'image' => null,                // ← NO IMAGE
            'short_description' => 'Original Dell 65W AC adapter for Inspiron and Vostro series.',
            'manufacturer' => 'Dell', 'model_number' => '65W Charger',
            'part_number' => 'ACC-CHR-DEL65', 'weight' => '300g',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 6 — HIGH-VALUE PRODUCT
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'MacBook Pro 14 M3 Motherboard 💰 HIGH VALUE',
            'slug' => 'macbook-pro-14-m3-motherboard',
            'code' => 'MB-APL-MBP14M3',
            'barcode' => 'MB-APL-MBP14M3',
            'brand_id' => $this->brand('Apple'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('ASUS-MB'),
            'purchase_price' => 65000, 'selling_price' => 85000,
            'minimum_stock_alert' => 1, 'stock_quantity' => 3,
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'Genuine Apple MacBook Pro 14-inch M3 logic board replacement.',
            'full_description'  => 'OEM Apple M3 chip logic board for MacBook Pro 14-inch 2023. Includes heatsink.',
            'manufacturer' => 'Apple', 'model_number' => 'MacBook Pro 14 M3',
            'part_number' => 'MB-APL-MBP14M3', 'warranty' => '3 Months',
            'weight' => '450g', 'country_of_origin' => 'USA',
        ]);

        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 7 — ZERO-PROFIT / LOSS PRODUCT
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Clearance: Old Stock Earphone 🏷️ ZERO PROFIT',
            'slug' => 'clearance-old-stock-earphone',
            'code' => 'ACC-EAR-CLR01',
            'barcode' => 'ACC-EAR-CLR01',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('EARPHONES'),
            'purchase_price' => 200, 'selling_price' => 200, // ← zero profit
            'minimum_stock_alert' => 5, 'stock_quantity' => 50,
            'image' => 'seed_earphone.jpg',
            'short_description' => 'Clearance sale — old stock wired earphones.',
            'manufacturer' => 'Generic', 'model_number' => 'EAR-CLR',
            'part_number' => 'ACC-EAR-CLR01', 'warranty' => 'No Warranty',
        ]);

        $products[] = $this->make([
            'name' => 'Clearance: Damaged Screen Guard 🏷️ LOSS',
            'slug' => 'clearance-damaged-screen-guard',
            'code' => 'ACC-SG-CLR02',
            'barcode' => 'ACC-SG-CLR02',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('SCREEN-GUARDS'),
            'purchase_price' => 80, 'selling_price' => 50, // ← selling below cost
            'minimum_stock_alert' => 10, 'stock_quantity' => 30,
            'image' => 'seed_tempered.jpg',
            'short_description' => 'Clearance sale — partial damage on packaging, product intact.',
            'manufacturer' => 'Generic', 'model_number' => 'SG-CLR',
            'part_number' => 'ACC-SG-CLR02', 'warranty' => 'No Warranty',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 8 — HIGH STOCK ACCESSORIES
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Type-C Fast Charging Cable 1m',
            'slug' => 'type-c-fast-charging-cable-1m',
            'code' => 'ACC-USB-TYPEC',
            'barcode' => 'ACC-USB-TYPEC',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('USB-CABLES'),
            'purchase_price' => 80, 'selling_price' => 150,
            'minimum_stock_alert' => 20, 'stock_quantity' => 200,
            'image' => 'seed_cable_usb.jpg',
            'short_description' => 'Braided USB Type-C 3A fast charging cable, 1 metre.',
            'manufacturer' => 'Generic', 'model_number' => 'USB-TC-1M',
            'part_number' => 'ACC-USB-TYPEC', 'weight' => '50g', 'warranty' => '1 Month',
        ]);

        $products[] = $this->make([
            'name' => 'Tempered Glass Screen Guard (Universal)',
            'slug' => 'tempered-glass-screen-guard',
            'code' => 'ACC-SG-TEMP',
            'barcode' => 'ACC-SG-TEMP',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('SCREEN-GUARDS'),
            'purchase_price' => 40, 'selling_price' => 99,
            'minimum_stock_alert' => 30, 'stock_quantity' => 350,
            'image' => 'seed_tempered.jpg',
            'short_description' => 'Ultra-thin 9H hardness tempered glass, anti-fingerprint.',
            'manufacturer' => 'Generic', 'model_number' => 'TG-UNIV',
            'part_number' => 'ACC-SG-TEMP', 'weight' => '15g', 'warranty' => 'No Warranty',
        ]);

        $products[] = $this->make([
            'name' => 'Silicone Phone Case (Universal)',
            'slug' => 'silicone-phone-case-universal',
            'code' => 'ACC-CASE-SIL',
            'barcode' => 'ACC-CASE-SIL',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('PHONE-CASES'),
            'purchase_price' => 60, 'selling_price' => 149,
            'minimum_stock_alert' => 25, 'stock_quantity' => 250,
            'image' => 'seed_cover.jpg',
            'short_description' => 'Flexible silicone phone case, shock absorption and grip.',
            'manufacturer' => 'Generic', 'model_number' => 'SIL-CASE-UNIV',
            'part_number' => 'ACC-CASE-SIL', 'weight' => '30g', 'warranty' => 'No Warranty',
        ]);

        $products[] = $this->make([
            'name' => 'Mi 20000mAh Power Bank',
            'slug' => 'mi-20000mah-power-bank',
            'code' => 'ACC-PB-MI20K',
            'barcode' => 'ACC-PB-MI20K',
            'brand_id' => $this->brand('Xiaomi'),
            'main_category_id' => $this->mainCat('ACCESSORIES-ITEM'),
            'sub_category_id' => $this->subCat('POWER-BANKS'),
            'purchase_price' => 800, 'selling_price' => 1299,
            'minimum_stock_alert' => 10, 'stock_quantity' => 95,
            'image' => 'seed_powerbank.jpg',
            'short_description' => 'Xiaomi Mi 20000mAh power bank with 18W fast charging.',
            'manufacturer' => 'Xiaomi', 'model_number' => 'Mi PB20',
            'part_number' => 'ACC-PB-MI20K', 'weight' => '440g', 'warranty' => '1 Year',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 9 — DISPLAYS (mix of stock levels)
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Redmi Note 12 LCD Display',
            'slug' => 'redmi-note-12-lcd-display',
            'code' => 'DSP-XIA-RN12',
            'barcode' => 'DSP-XIA-RN12',
            'brand_id' => $this->brand('Xiaomi'),
            'main_category_id' => $this->mainCat('DISPLAY'),
            'sub_category_id' => $this->subCat('MOBILE-DISPLAY'),
            'purchase_price' => 1800, 'selling_price' => 2800,
            'minimum_stock_alert' => 5, 'stock_quantity' => 25,
            'image' => 'seed_display_mobile.jpg',
            'short_description' => 'Compatible LCD display with touch digitizer for Redmi Note 12.',
            'manufacturer' => 'Xiaomi', 'model_number' => 'Redmi Note 12',
            'part_number' => 'DSP-XIA-RN12', 'weight' => '85g', 'warranty' => '3 Months',
        ]);

        $products[] = $this->make([
            'name' => 'Dell Inspiron 15 FHD Display',
            'slug' => 'dell-inspiron-15-fhd-display',
            'code' => 'DSP-DEL-INS15',
            'barcode' => 'DSP-DEL-INS15',
            'brand_id' => $this->brand('Dell'),
            'main_category_id' => $this->mainCat('DISPLAY'),
            'sub_category_id' => $this->subCat('LAPTOP-DISPLAY'),
            'purchase_price' => 3500, 'selling_price' => 5200,
            'minimum_stock_alert' => 3, 'stock_quantity' => 18,
            'image' => 'seed_display_laptop.jpg',
            'short_description' => 'Original 15.6" FHD IPS display panel for Dell Inspiron 15 series.',
            'manufacturer' => 'Dell', 'model_number' => 'Inspiron 15',
            'part_number' => 'DSP-DEL-INS15', 'weight' => '350g',
        ]);

        $products[] = $this->make([
            'name' => 'HP Pavilion 15 Display Panel',
            'slug' => 'hp-pavilion-15-display-panel',
            'code' => 'DSP-HP-PAV15',
            'barcode' => 'DSP-HP-PAV15',
            'brand_id' => $this->brand('HP'),
            'main_category_id' => $this->mainCat('DISPLAY'),
            'sub_category_id' => $this->subCat('LAPTOP-DISPLAY'),
            'purchase_price' => 3200, 'selling_price' => 4800,
            'minimum_stock_alert' => 3, 'stock_quantity' => 15,
            'image' => 'seed_display_laptop.jpg',
            'short_description' => 'Compatible 15.6" FHD display panel for HP Pavilion 15 series.',
            'manufacturer' => 'HP', 'model_number' => 'Pavilion 15',
            'part_number' => 'DSP-HP-PAV15', 'weight' => '340g',
        ]);

        /* ═══════════════════════════════════════════════════════════════
         *  SCENARIO 10 — COMPUTER HARDWARE
         * ═══════════════════════════════════════════════════════════════ */
        $products[] = $this->make([
            'name' => 'Samsung 8GB DDR4 RAM 3200MHz',
            'slug' => 'samsung-8gb-ddr4-ram-3200mhz',
            'code' => 'RAM-SAM-8G3200',
            'barcode' => 'RAM-SAM-8G3200',
            'brand_id' => $this->brand('Samsung'),
            'main_category_id' => $this->mainCat('ELECTRONICE-ITEM'),
            'sub_category_id' => $this->subCat('RAM'),
            'purchase_price' => 1800, 'selling_price' => 2499,
            'minimum_stock_alert' => 5, 'stock_quantity' => 20,
            'image' => 'seed_ram.jpg',
            'short_description' => 'Samsung 8GB DDR4 DIMM 3200MHz desktop memory module.',
            'manufacturer' => 'Samsung', 'model_number' => 'M378A1K43EB2',
            'part_number' => 'RAM-SAM-8G3200', 'warranty' => 'Lifetime',
            'weight' => '40g', 'country_of_origin' => 'South Korea',
        ]);

        $products[] = $this->make([
            'name' => 'Seagate 1TB SATA HDD',
            'slug' => 'seagate-1tb-sata-hdd',
            'code' => 'HDD-SEA-1TB',
            'barcode' => 'HDD-SEA-1TB',
            'brand_id' => $this->brand('Generic'),
            'main_category_id' => $this->mainCat('ELECTRONICE-ITEM'),
            'sub_category_id' => $this->subCat('HDD'),
            'purchase_price' => 2800, 'selling_price' => 3800,
            'minimum_stock_alert' => 3, 'stock_quantity' => 15,
            'image' => 'seed_ssd.jpg',
            'short_description' => 'Seagate Barracuda 1TB 3.5-inch SATA 7200RPM hard drive.',
            'manufacturer' => 'Seagate', 'model_number' => 'ST1000DM010',
            'part_number' => 'HDD-SEA-1TB', 'warranty' => '2 Years',
            'weight' => '430g', 'country_of_origin' => 'China',
        ]);

        $products[] = $this->make([
            'name' => 'Samsung 256GB SSD SATA',
            'slug' => 'samsung-256gb-ssd-sata',
            'code' => 'SSD-SAM-256G',
            'barcode' => 'SSD-SAM-256G',
            'brand_id' => $this->brand('Samsung'),
            'main_category_id' => $this->mainCat('ELECTRONICE-ITEM'),
            'sub_category_id' => $this->subCat('SSD'),
            'purchase_price' => 2200, 'selling_price' => 3200,
            'minimum_stock_alert' => 3, 'stock_quantity' => 18,
            'image' => 'seed_ssd.jpg',
            'short_description' => 'Samsung 870 EVO 256GB SATA III 2.5" SSD, up to 560MB/s.',
            'manufacturer' => 'Samsung', 'model_number' => 'MZ-77E250BW',
            'part_number' => 'SSD-SAM-256G', 'warranty' => '5 Years',
            'weight' => '55g', 'country_of_origin' => 'South Korea',
        ]);

        $products[] = $this->make([
            'name' => 'Asus VivoBook 15 Motherboard',
            'slug' => 'asus-vivobook-15-motherboard',
            'code' => 'MB-ASU-VB15',
            'barcode' => 'MB-ASU-VB15',
            'brand_id' => $this->brand('Asus'),
            'main_category_id' => $this->mainCat('LAPTOP-MB'),
            'sub_category_id' => $this->subCat('ASUS-MB'),
            'purchase_price' => 4500, 'selling_price' => 6500,
            'minimum_stock_alert' => 3, 'stock_quantity' => 12,
            'image' => 'seed_laptop_mb.jpg',
            'short_description' => 'Genuine OEM replacement motherboard for Asus VivoBook 15 series.',
            'manufacturer' => 'Asus', 'model_number' => 'VivoBook 15',
            'part_number' => 'MB-ASU-VB15', 'weight' => '350g',
        ]);


        /* ═══════════════════════════════════════════════════════════════
         *  INSERT ALL
         * ═══════════════════════════════════════════════════════════════ */
        foreach ($products as $data) {
            $stockQty = $data['stock_quantity'] ?? 0;
            unset($data['stock_quantity']);

            // gallery needs to be JSON-ready
            if (!isset($data['gallery'])) {
                $data['gallery'] = [];
            }

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                $data
            );

            Stock::updateOrCreate(
                ['product_id' => $product->id],
                ['quantity'   => $stockQty]
            );
        }

        $this->command->info('✅ ProductSeeder done — ' . count($products) . ' products seeded.');
        $this->command->line('   Scenarios covered:');
        $this->command->line('   ✅ Normal stock    — Samsung Galaxy A53 MB, Dell Inspiron 15 MB, HP Pavilion 14 MB, Xiaomi Redmi Note 12 MB');
        $this->command->line('   ⚠️  Low stock      — Asus TUF F15 MB, iPhone 13 Display, Lenovo IdeaPad 3 MB, Intel i5-12400');
        $this->command->line('   ❌  Out of stock   — OnePlus Nord CE3 MB, AMD RX 6600 GPU, Samsung A53 Display');
        $this->command->line('   🔴  Inactive       — Realme 11 Pro MB, Oppo A78 MB');
        $this->command->line('   🖼️   No image       — Generic USB Hub, Dell Charger');
        $this->command->line('   💰  High value     — MacBook Pro 14 M3 MB');
        $this->command->line('   🏷️   Zero/Loss      — Clearance Earphone, Clearance Screen Guard');
        $this->command->line('   🔢  High stock     — Type-C Cable, Tempered Glass, Silicone Case, Mi Power Bank');
        $this->command->line('   🌐  Multi-category — Displays (mobile+laptop), Computer hardware (RAM/HDD/SSD/GPU)');
    }
}
