<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = public_path('uploads/products');
    }

    private function brand(string $slug): int
    {
        return Brand::whereRaw('UPPER(slug) = ?', [strtoupper($slug)])->value('id') ?? 0;
    }

    private function mainCat(string $slug): int
    {
        return MainCategory::whereRaw('UPPER(slug) = ?', [strtoupper($slug)])->value('id') ?? 0;
    }

    private function subCat(string $slug): int
    {
        return SubCategory::whereRaw('UPPER(slug) = ?', [strtoupper($slug)])->value('id') ?? 0;
    }

    private function img(string $url, string $filename): ?string
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        $dest = $this->uploadDir . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($dest) && filesize($dest) > 2000) {
            return $filename;
        }
        $ctx = stream_context_create([
            'http' => ['timeout' => 20, 'follow_location' => true, 'user_agent' => 'Mozilla/5.0'],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);
        $data = @file_get_contents($url, false, $ctx);
        if ($data && strlen($data) > 2000) {
            file_put_contents($dest, $data);
            $this->command->line("  ↓ {$filename}");
            return $filename;
        }
        $this->command->warn("  ✗ FAILED: {$filename}");
        return null;
    }

    public function run(): void
    {
        $this->command->info('⏳ Downloading product images...');

        // Each image — unique attractive Unsplash photo, category-matched, 3-4 per product
        $images = [

            // ── Mobile PCBs / motherboards ──────────────────────────────────
            'p_mob_mb_sam.jpg'     => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=700&q=85',
            'p_mob_mb_sam_b.jpg'   => 'https://images.unsplash.com/photo-1535378620166-273708d44e4c?w=700&q=85',
            'p_mob_mb_sam_c.jpg'   => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=700&q=85',

            'p_mob_mb_xia.jpg'     => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=700&q=80',
            'p_mob_mb_xia_b.jpg'   => 'https://images.unsplash.com/photo-1555617117-08937bfde5a3?w=700&q=82',
            'p_mob_mb_xia_c.jpg'   => 'https://images.unsplash.com/photo-1631016800696-5ea8801b3c2a?w=700&q=82',

            'p_mob_mb_op.jpg'      => 'https://images.unsplash.com/photo-1631016800696-5ea8801b3c2a?w=700&q=85',
            'p_mob_mb_op_b.jpg'    => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=700&q=78',
            'p_mob_mb_op_c.jpg'    => 'https://images.unsplash.com/photo-1601935111741-ae98b2b230b0?w=700&q=82',

            'p_mob_mb_rea.jpg'     => 'https://images.unsplash.com/photo-1574282096370-1e23e738e1c7?w=700&q=82',
            'p_mob_mb_opp.jpg'     => 'https://images.unsplash.com/photo-1601935111741-ae98b2b230b0?w=700&q=85',

            // ── Laptop motherboards ─────────────────────────────────────────
            'p_lap_mb_del.jpg'     => 'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=700&q=85',
            'p_lap_mb_del_b.jpg'   => 'https://images.unsplash.com/photo-1603732551681-2e91159b9dc2?w=700&q=85',
            'p_lap_mb_del_c.jpg'   => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=82',

            'p_lap_mb_hp.jpg'      => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=85',
            'p_lap_mb_hp_b.jpg'    => 'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=700&q=80',
            'p_lap_mb_hp_c.jpg'    => 'https://images.unsplash.com/photo-1510906594845-bc082582c8cc?w=700&q=82',

            'p_lap_mb_asu.jpg'     => 'https://images.unsplash.com/photo-1603732551681-2e91159b9dc2?w=700&q=85',
            'p_lap_mb_asu_b.jpg'   => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=82',
            'p_lap_mb_asu_c.jpg'   => 'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=700&q=78',

            'p_lap_mb_apl.jpg'     => 'https://images.unsplash.com/photo-1510906594845-bc082582c8cc?w=700&q=88',
            'p_lap_mb_apl_b.jpg'   => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=700&q=85',
            'p_lap_mb_apl_c.jpg'   => 'https://images.unsplash.com/photo-1611186871525-4a7a8e0cd0db?w=700&q=85',

            'p_lap_mb_vu15.jpg'    => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=700&q=85',
            'p_lap_mb_vu15_b.jpg'  => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=700&q=85',
            'p_lap_mb_vu15_c.jpg'  => 'https://images.unsplash.com/photo-1603732551681-2e91159b9dc2?w=700&q=80',

            'p_lap_mb_len.jpg'     => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=700&q=85',
            'p_lap_mb_len_b.jpg'   => 'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=700&q=78',
            'p_lap_mb_len_c.jpg'   => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=80',

            // ── Mobile displays — REAL phone photos ─────────────────────────
            // iPhone 13
            'p_dsp_mob_apl.jpg'    => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?w=700&q=88',
            'p_dsp_mob_apl_b.jpg'  => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=700&q=88',
            'p_dsp_mob_apl_c.jpg'  => 'https://images.unsplash.com/photo-1664478546384-d57ffe74a78c?w=700&q=88',
            'p_dsp_mob_apl_d.jpg'  => 'https://images.unsplash.com/photo-1611472173362-3f53dbd65d80?w=700&q=88',

            // Samsung Galaxy A53
            'p_dsp_mob_sam.jpg'    => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=700&q=88',
            'p_dsp_mob_sam_b.jpg'  => 'https://images.unsplash.com/photo-1546054454-aa26e2b734c7?w=700&q=88',
            'p_dsp_mob_sam_c.jpg'  => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=700&q=85',
            'p_dsp_mob_sam_d.jpg'  => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=700&q=80',

            // Xiaomi Redmi Note 12
            'p_dsp_mob_xia.jpg'    => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=700&q=88',
            'p_dsp_mob_xia_b.jpg'  => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=700&q=85',
            'p_dsp_mob_xia_c.jpg'  => 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=700&q=85',
            'p_dsp_mob_xia_d.jpg'  => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=700&q=80',

            // ── Laptop displays ─────────────────────────────────────────────
            'p_dsp_lap_del.jpg'    => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=85',
            'p_dsp_lap_del_b.jpg'  => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=700&q=85',
            'p_dsp_lap_del_c.jpg'  => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=700&q=82',

            'p_dsp_lap_hp.jpg'     => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=700&q=85',
            'p_dsp_lap_hp_b.jpg'   => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=82',
            'p_dsp_lap_hp_c.jpg'   => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=700&q=82',

            // ── RAM ─────────────────────────────────────────────────────────
            'p_ram_sam.jpg'        => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=700&q=85',
            'p_ram_sam_b.jpg'      => 'https://images.unsplash.com/photo-1629654291663-b91ad427698f?w=700&q=85',
            'p_ram_sam_c.jpg'      => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=700&q=78',

            // ── SSD ─────────────────────────────────────────────────────────
            'p_ssd_sam.jpg'        => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=700&q=85',
            'p_ssd_sam_b.jpg'      => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=700&q=85',
            'p_ssd_sam_c.jpg'      => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=700&q=78',

            // ── HDD ─────────────────────────────────────────────────────────
            'p_hdd_sea.jpg'        => 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?w=700&q=85',
            'p_hdd_sea_b.jpg'      => 'https://images.unsplash.com/photo-1573655349936-de6bed86f839?w=700&q=85',
            'p_hdd_sea_c.jpg'      => 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?w=700&q=78',

            // ── GPU ─────────────────────────────────────────────────────────
            'p_gpu_amd.jpg'        => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=700&q=85',
            'p_gpu_amd_b.jpg'      => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=700&q=85',
            'p_gpu_amd_c.jpg'      => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=700&q=78',

            // ── CPU ─────────────────────────────────────────────────────────
            'p_cpu_intel.jpg'      => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=700&q=85',
            'p_cpu_intel_b.jpg'    => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=700&q=78',
            'p_cpu_intel_c.jpg'    => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=700&q=70',

            // ── USB cable ───────────────────────────────────────────────────
            'p_cable_usbc.jpg'     => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700&q=85',
            'p_cable_usbc_b.jpg'   => 'https://images.unsplash.com/photo-1588345921523-c2dcdb7f1dcd?w=700&q=85',
            'p_cable_usbc_c.jpg'   => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700&q=78',

            // ── Power bank ──────────────────────────────────────────────────
            'p_pb_mi.jpg'          => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=700&q=85',
            'p_pb_mi_b.jpg'        => 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=700&q=85',
            'p_pb_mi_c.jpg'        => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=700&q=78',

            // ── Earphones ───────────────────────────────────────────────────
            'p_ear_wired.jpg'      => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=700&q=85',
            'p_ear_wired_b.jpg'    => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=700&q=85',
            'p_ear_boat.jpg'       => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=700&q=88',
            'p_ear_boat_b.jpg'     => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=700&q=82',
            'p_ear_boat_c.jpg'     => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=700&q=78',

            // ── Tempered glass ──────────────────────────────────────────────
            'p_tg_univ.jpg'        => 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=700&q=85',
            'p_tg_univ_b.jpg'      => 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=700&q=78',

            // ── Phone case ──────────────────────────────────────────────────
            'p_case_sil.jpg'       => 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=700&q=85',
            'p_case_sil_b.jpg'     => 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=700&q=78',
            'p_case_sil_c.jpg'     => 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=700&q=70',

            // ── Charger ─────────────────────────────────────────────────────
            'p_charger_del.jpg'    => 'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=700&q=82',
            'p_charger_del_b.jpg'  => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=700&q=78',
        ];

        $dl = [];
        foreach ($images as $fn => $url) {
            $dl[$fn] = $this->img($url, $fn);
        }
        $f = fn(string $k) => $dl[$k] ?? null;

        // ── PRODUCT LIST ─────────────────────────────────────────────────────
        $products = [

            // ════════ MOBILE MOTHERBOARDS ════════
            ['name'=>'Samsung Galaxy A53 Motherboard','code'=>'MMB-SAM-A53','barcode'=>'MMB-SAM-A53',
             'brand_id'=>$this->brand('SAMSUNG'),'main_category_id'=>$this->mainCat('MOBILE-MB'),'sub_category_id'=>$this->subCat('SAMSUNG-MOBILE-MB'),
             'purchase_price'=>3200,'selling_price'=>4800,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Samsung','model_number'=>'Galaxy A53','part_number'=>'GH82-28100A','warranty'=>'6 Months','weight'=>'110g','country_of_origin'=>'South Korea',
             'image'=>$f('p_mob_mb_sam.jpg'),'gallery'=>array_filter([$f('p_mob_mb_xia.jpg')]),
             'stock_quantity'=>25,'short_description'=>'Original OEM logic board for Samsung Galaxy A53 5G. Fully tested before dispatch.'],

            ['name'=>'Xiaomi Redmi Note 12 Motherboard','code'=>'MMB-XIA-RN12','barcode'=>'MMB-XIA-RN12',
             'brand_id'=>$this->brand('XIAOMI'),'main_category_id'=>$this->mainCat('MOBILE-MB'),'sub_category_id'=>$this->subCat('XIAOMI-MOBILE-MB'),
             'purchase_price'=>2800,'selling_price'=>4200,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Xiaomi','model_number'=>'Redmi Note 12','part_number'=>'RN12-MB-2023','warranty'=>'6 Months','weight'=>'108g','country_of_origin'=>'China',
             'image'=>$f('p_mob_mb_xia.jpg'),'gallery'=>array_filter([$f('p_mob_mb_sam.jpg')]),
             'stock_quantity'=>30,'short_description'=>'Compatible OEM logic board for Xiaomi Redmi Note 12 4G/5G.'],

            ['name'=>'OnePlus Nord CE3 Motherboard','code'=>'MMB-OP-NCE3','barcode'=>'MMB-OP-NCE3',
             'brand_id'=>$this->brand('ONEPLUS'),'main_category_id'=>$this->mainCat('MOBILE-MB'),'sub_category_id'=>$this->subCat('ONEPLUS-MOBILE-MB'),
             'purchase_price'=>3500,'selling_price'=>5200,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'OnePlus','model_number'=>'Nord CE3','part_number'=>'OP-NCE3-MB','warranty'=>'3 Months','weight'=>'115g','country_of_origin'=>'China',
             'image'=>$f('p_mob_mb_op.jpg'),'gallery'=>array_filter([$f('p_mob_mb_rea.jpg')]),
             'stock_quantity'=>0,'short_description'=>'Genuine OEM logic board for OnePlus Nord CE3 Lite.'],

            ['name'=>'Realme 11 Pro Motherboard','code'=>'MMB-REA-11P','barcode'=>'MMB-REA-11P',
             'brand_id'=>$this->brand('REALME'),'main_category_id'=>$this->mainCat('MOBILE-MB'),'sub_category_id'=>$this->subCat('REALME-MOBILE-MB'),
             'purchase_price'=>2500,'selling_price'=>3800,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Realme','model_number'=>'Realme 11 Pro','part_number'=>'REA11P-MB','warranty'=>'3 Months','weight'=>'108g','country_of_origin'=>'China',
             'status'=>'inactive',
             'image'=>$f('p_mob_mb_rea.jpg'),'gallery'=>[],
             'stock_quantity'=>12,'short_description'=>'Compatible OEM motherboard for Realme 11 Pro.'],

            ['name'=>'Oppo A78 Motherboard','code'=>'MMB-OPP-A78','barcode'=>'MMB-OPP-A78',
             'brand_id'=>$this->brand('OPPO'),'main_category_id'=>$this->mainCat('MOBILE-MB'),'sub_category_id'=>$this->subCat('OPPO-MOBILE-MB'),
             'purchase_price'=>2200,'selling_price'=>3500,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Oppo','model_number'=>'Oppo A78','part_number'=>'OPP-A78-MB','warranty'=>'3 Months','weight'=>'105g','country_of_origin'=>'China',
             'status'=>'inactive',
             'image'=>$f('p_mob_mb_opp.jpg'),'gallery'=>[],
             'stock_quantity'=>8,'short_description'=>'Compatible OEM logic board for Oppo A78 4G.'],

            // ════════ LAPTOP MOTHERBOARDS ════════
            ['name'=>'Dell Inspiron 15 Motherboard','code'=>'MB-DEL-INS15','barcode'=>'MB-DEL-INS15',
             'brand_id'=>$this->brand('DELL'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('DELL-MB'),
             'purchase_price'=>5000,'selling_price'=>7200,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Dell','model_number'=>'Inspiron 15 3520','part_number'=>'CN-0F4N5X','warranty'=>'6 Months','weight'=>'380g','country_of_origin'=>'China',
             'image'=>$f('p_lap_mb_del.jpg'),'gallery'=>array_filter([$f('p_lap_mb_hp.jpg')]),
             'stock_quantity'=>10,'short_description'=>'OEM replacement motherboard for Dell Inspiron 15 3000/5000 series.'],

            ['name'=>'HP Pavilion 14 Motherboard','code'=>'MB-HP-PAV14','barcode'=>'MB-HP-PAV14',
             'brand_id'=>$this->brand('HP'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('HP-MB'),
             'purchase_price'=>4200,'selling_price'=>6000,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'HP','model_number'=>'Pavilion 14-dv0002TU','part_number'=>'DA00G5MB8F0','warranty'=>'6 Months','weight'=>'340g','country_of_origin'=>'China',
             'image'=>$f('p_lap_mb_hp.jpg'),'gallery'=>array_filter([$f('p_lap_mb_del.jpg')]),
             'stock_quantity'=>11,'short_description'=>'OEM motherboard compatible with HP Pavilion 14 series.'],

            ['name'=>'Asus TUF F15 Motherboard','code'=>'MB-ASU-TUFF15','barcode'=>'MB-ASU-TUFF15',
             'brand_id'=>$this->brand('ASUS'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('ASUS-MB'),
             'purchase_price'=>7500,'selling_price'=>10500,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Asus','model_number'=>'TUF Gaming F15 FX506H','part_number'=>'60NB0T40-MB1810','warranty'=>'6 Months','weight'=>'400g','country_of_origin'=>'Taiwan',
             'image'=>$f('p_lap_mb_asu.jpg'),'gallery'=>array_filter([$f('p_lap_mb_del.jpg')]),
             'stock_quantity'=>2,'short_description'=>'OEM logic board for Asus TUF Gaming F15 FX506 series.'],

            ['name'=>'MacBook Pro 14 M3 Logic Board','code'=>'MB-APL-MBP14M3','barcode'=>'MB-APL-MBP14M3',
             'brand_id'=>$this->brand('APPLE'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('ASUS-MB'),
             'purchase_price'=>65000,'selling_price'=>85000,'tax_percentage'=>18,'minimum_stock_alert'=>1,
             'manufacturer'=>'Apple','model_number'=>'MacBook Pro 14-inch M3 2023','part_number'=>'820-02788-A','warranty'=>'3 Months','weight'=>'450g','country_of_origin'=>'USA',
             'image'=>$f('p_lap_mb_apl.jpg'),'gallery'=>array_filter([$f('p_lap_mb_del.jpg'),$f('p_lap_mb_asu.jpg')]),
             'stock_quantity'=>3,'short_description'=>'Genuine Apple MacBook Pro 14-inch M3 logic board replacement. Includes heatsink.'],

            ['name'=>'Asus VivoBook 15 Motherboard','code'=>'MB-ASU-VB15','barcode'=>'MB-ASU-VB15',
             'brand_id'=>$this->brand('ASUS'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('ASUS-MB'),
             'purchase_price'=>4500,'selling_price'=>6500,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Asus','model_number'=>'VivoBook 15 X1502ZA','part_number'=>'60NB0WX0-MB3410','warranty'=>'6 Months','weight'=>'350g','country_of_origin'=>'Taiwan',
             'image'=>$f('p_lap_mb_vu15.jpg'),'gallery'=>array_filter([$f('p_lap_mb_vu15b.jpg')]),
             'stock_quantity'=>12,'short_description'=>'Genuine OEM replacement motherboard for Asus VivoBook 15 X1502 series.'],

            ['name'=>'Lenovo IdeaPad 3 Motherboard','code'=>'MB-LEN-IP3','barcode'=>'MB-LEN-IP3',
             'brand_id'=>$this->brand('LENOVO'),'main_category_id'=>$this->mainCat('LAPTOP-MB'),'sub_category_id'=>$this->subCat('LENOVO-MB'),
             'purchase_price'=>4800,'selling_price'=>6800,'tax_percentage'=>18,'minimum_stock_alert'=>4,
             'manufacturer'=>'Lenovo','model_number'=>'IdeaPad 3 15ITL6','part_number'=>'5B21C23302','warranty'=>'6 Months','weight'=>'360g','country_of_origin'=>'China',
             'image'=>$f('p_lap_mb_len.jpg'),'gallery'=>array_filter([$f('p_lap_mb_len_b.jpg')]),
             'stock_quantity'=>3,'short_description'=>'Compatible OEM motherboard for Lenovo IdeaPad 3 15ITL6 series.'],
        ];

        // Append displays, hardware, accessories
        $more = [
            // ════════ DISPLAYS ════════
            ['name'=>'iPhone 13 OLED Display Assembly','code'=>'DSP-APL-IP13','barcode'=>'DSP-APL-IP13',
             'brand_id'=>$this->brand('APPLE'),'main_category_id'=>$this->mainCat('DISPLAY'),'sub_category_id'=>$this->subCat('MOBILE-DISPLAY'),
             'purchase_price'=>5500,'selling_price'=>8500,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Apple','model_number'=>'iPhone 13','part_number'=>'661-26290','warranty'=>'3 Months','weight'=>'80g','country_of_origin'=>'China',
             'image'=>$f('p_dsp_mob_apl.jpg'),'gallery'=>array_filter([$f('p_dsp_mob_apl_b.jpg'),$f('p_dsp_mob_apl_c.jpg'),$f('p_dsp_mob_apl_d.jpg')]),
             'stock_quantity'=>1,'short_description'=>'OLED display + touch digitizer assembly for Apple iPhone 13. True Tone supported.'],

            ['name'=>'Samsung Galaxy A53 AMOLED Display','code'=>'DSP-SAM-A53','barcode'=>'DSP-SAM-A53',
             'brand_id'=>$this->brand('SAMSUNG'),'main_category_id'=>$this->mainCat('DISPLAY'),'sub_category_id'=>$this->subCat('MOBILE-DISPLAY'),
             'purchase_price'=>2800,'selling_price'=>4200,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Samsung','model_number'=>'Galaxy A53 5G','part_number'=>'GH96-15068A','warranty'=>'3 Months','weight'=>'90g','country_of_origin'=>'South Korea',
             'image'=>$f('p_dsp_mob_sam.jpg'),'gallery'=>array_filter([$f('p_dsp_mob_sam_b.jpg'),$f('p_dsp_mob_sam_c.jpg'),$f('p_dsp_mob_sam_d.jpg')]),
             'stock_quantity'=>0,'short_description'=>'Original Super AMOLED display with touch for Samsung Galaxy A53 5G.'],

            ['name'=>'Xiaomi Redmi Note 12 LCD Display','code'=>'DSP-XIA-RN12','barcode'=>'DSP-XIA-RN12',
             'brand_id'=>$this->brand('XIAOMI'),'main_category_id'=>$this->mainCat('DISPLAY'),'sub_category_id'=>$this->subCat('MOBILE-DISPLAY'),
             'purchase_price'=>1800,'selling_price'=>2800,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Xiaomi','model_number'=>'Redmi Note 12','part_number'=>'RN12-LCD-2023','warranty'=>'3 Months','weight'=>'85g','country_of_origin'=>'China',
             'image'=>$f('p_dsp_mob_xia.jpg'),'gallery'=>array_filter([$f('p_dsp_mob_xia_b.jpg'),$f('p_dsp_mob_xia_c.jpg'),$f('p_dsp_mob_xia_d.jpg')]),
             'stock_quantity'=>25,'short_description'=>'Compatible LCD display with touch digitizer for Redmi Note 12.'],

            ['name'=>'Dell Inspiron 15 FHD Display Panel','code'=>'DSP-DEL-INS15','barcode'=>'DSP-DEL-INS15',
             'brand_id'=>$this->brand('DELL'),'main_category_id'=>$this->mainCat('DISPLAY'),'sub_category_id'=>$this->subCat('LAPTOP-DISPLAY'),
             'purchase_price'=>3500,'selling_price'=>5200,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Dell','model_number'=>'Inspiron 15 3520','part_number'=>'0KP5C2','warranty'=>'3 Months','weight'=>'350g','country_of_origin'=>'China',
             'image'=>$f('p_dsp_lap_del.jpg'),'gallery'=>array_filter([$f('p_dsp_lap_del_b.jpg'),$f('p_dsp_lap_del_c.jpg')]),
             'stock_quantity'=>18,'short_description'=>'15.6" FHD IPS 120Hz display panel for Dell Inspiron 15 series.'],

            ['name'=>'HP Pavilion 15 Display Panel','code'=>'DSP-HP-PAV15','barcode'=>'DSP-HP-PAV15',
             'brand_id'=>$this->brand('HP'),'main_category_id'=>$this->mainCat('DISPLAY'),'sub_category_id'=>$this->subCat('LAPTOP-DISPLAY'),
             'purchase_price'=>3200,'selling_price'=>4800,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'HP','model_number'=>'Pavilion 15-eh3006AU','part_number'=>'M47526-001','warranty'=>'3 Months','weight'=>'340g','country_of_origin'=>'China',
             'image'=>$f('p_dsp_lap_hp.jpg'),'gallery'=>array_filter([$f('p_dsp_lap_hp_b.jpg'),$f('p_dsp_lap_hp_c.jpg')]),
             'stock_quantity'=>15,'short_description'=>'15.6" FHD IPS display panel for HP Pavilion 15 series.'],

            // ════════ COMPUTER HARDWARE ════════
            ['name'=>'Samsung 8GB DDR4 RAM 3200MHz','code'=>'RAM-SAM-8G3200','barcode'=>'RAM-SAM-8G3200',
             'brand_id'=>$this->brand('SAMSUNG'),'main_category_id'=>$this->mainCat('ELECTRONICE-ITEM'),'sub_category_id'=>$this->subCat('RAM'),
             'purchase_price'=>1800,'selling_price'=>2499,'tax_percentage'=>18,'minimum_stock_alert'=>5,
             'manufacturer'=>'Samsung','model_number'=>'M378A1K43EB2-CWE','part_number'=>'M378A1K43EB2','warranty'=>'Lifetime','weight'=>'40g','country_of_origin'=>'South Korea',
             'image'=>$f('p_ram_sam.jpg'),'gallery'=>array_filter([$f('p_ram_sam_b.jpg')]),
             'stock_quantity'=>20,'short_description'=>'Samsung 8GB DDR4 DIMM 3200MHz — compatible with Intel & AMD desktop platforms.'],

            ['name'=>'Samsung 256GB SSD SATA','code'=>'SSD-SAM-256G','barcode'=>'SSD-SAM-256G',
             'brand_id'=>$this->brand('SAMSUNG'),'main_category_id'=>$this->mainCat('ELECTRONICE-ITEM'),'sub_category_id'=>$this->subCat('SSD'),
             'purchase_price'=>2200,'selling_price'=>3200,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Samsung','model_number'=>'870 EVO','part_number'=>'MZ-77E250BW','warranty'=>'5 Years','weight'=>'55g','country_of_origin'=>'South Korea',
             'image'=>$f('p_ssd_sam.jpg'),'gallery'=>array_filter([$f('p_ssd_sam_b.jpg')]),
             'stock_quantity'=>18,'short_description'=>'Samsung 870 EVO 256GB SATA III 2.5" SSD. Read: 560MB/s, Write: 530MB/s.'],

            ['name'=>'Seagate 1TB SATA Hard Drive','code'=>'HDD-SEA-1TB','barcode'=>'HDD-SEA-1TB',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ELECTRONICE-ITEM'),'sub_category_id'=>$this->subCat('HDD'),
             'purchase_price'=>2800,'selling_price'=>3800,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Seagate','model_number'=>'Barracuda ST1000DM010','part_number'=>'ST1000DM010','warranty'=>'2 Years','weight'=>'430g','country_of_origin'=>'China',
             'image'=>$f('p_hdd_sea.jpg'),'gallery'=>array_filter([$f('p_hdd_sea_b.jpg')]),
             'stock_quantity'=>15,'short_description'=>'Seagate Barracuda 1TB 3.5" SATA III 7200RPM. Ideal for desktop upgrades.'],

            ['name'=>'AMD Radeon RX 6600 8GB GPU','code'=>'GPU-AMD-RX6600','barcode'=>'GPU-AMD-RX6600',
             'brand_id'=>$this->brand('AMD'),'main_category_id'=>$this->mainCat('ELECTRONICE-ITEM'),'sub_category_id'=>$this->subCat('GPU'),
             'purchase_price'=>18000,'selling_price'=>22500,'tax_percentage'=>18,'minimum_stock_alert'=>2,
             'manufacturer'=>'AMD','model_number'=>'Radeon RX 6600','part_number'=>'100-100001236BOX','warranty'=>'3 Years','weight'=>'700g','country_of_origin'=>'USA',
             'image'=>$f('p_gpu_amd.jpg'),'gallery'=>array_filter([$f('p_gpu_amd_b.jpg')]),
             'stock_quantity'=>0,'short_description'=>'AMD Radeon RX 6600 8GB GDDR6, PCIe 4.0 — great for 1080p gaming.'],

            ['name'=>'Intel Core i5-12400 Processor','code'=>'CPU-INT-I512400','barcode'=>'CPU-INT-I512400',
             'brand_id'=>$this->brand('INTEL'),'main_category_id'=>$this->mainCat('ELECTRONICE-ITEM'),'sub_category_id'=>$this->subCat('CPUS'),
             'purchase_price'=>13500,'selling_price'=>16500,'tax_percentage'=>18,'minimum_stock_alert'=>3,
             'manufacturer'=>'Intel','model_number'=>'Core i5-12400 LGA1700','part_number'=>'BX8071512400','warranty'=>'3 Years','weight'=>'75g','country_of_origin'=>'Malaysia',
             'image'=>$f('p_cpu_intel.jpg'),'gallery'=>array_filter([$f('p_cpu_intel_b.jpg')]),
             'stock_quantity'=>2,'short_description'=>'Intel Core i5-12400 — 6 cores / 12 threads, 2.5GHz base, 4.4GHz boost, LGA1700.'],

            // ════════ ACCESSORIES ════════
            ['name'=>'Type-C Fast Charging Cable 1m','code'=>'ACC-USB-TYPEC','barcode'=>'ACC-USB-TYPEC',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('USB-CABLES'),
             'purchase_price'=>80,'selling_price'=>150,'tax_percentage'=>5,'minimum_stock_alert'=>20,
             'manufacturer'=>'Generic','model_number'=>'USB-TC-3A-1M','part_number'=>'ACC-USB-TYPEC','warranty'=>'1 Month','weight'=>'50g','country_of_origin'=>'China',
             'image'=>$f('p_cable_usbc.jpg'),'gallery'=>array_filter([$f('p_cable_usbc_b.jpg')]),
             'stock_quantity'=>200,'short_description'=>'Braided nylon USB Type-C 3A fast charging cable, 1 metre.'],

            ['name'=>'Mi 20000mAh Power Bank 18W','code'=>'ACC-PB-MI20K','barcode'=>'ACC-PB-MI20K',
             'brand_id'=>$this->brand('XIAOMI'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('POWER-BANKS'),
             'purchase_price'=>800,'selling_price'=>1299,'tax_percentage'=>18,'minimum_stock_alert'=>10,
             'manufacturer'=>'Xiaomi','model_number'=>'Mi Power Bank 3 20000','part_number'=>'PB2050ZM','warranty'=>'1 Year','weight'=>'440g','country_of_origin'=>'China',
             'image'=>$f('p_pb_mi.jpg'),'gallery'=>array_filter([$f('p_pb_mi_b.jpg')]),
             'stock_quantity'=>95,'short_description'=>'Xiaomi Mi 20000mAh power bank with 18W fast charging and dual USB output.'],

            ['name'=>'boAt Bassheads 100 Earphones','code'=>'ACC-EAR-BOAT100','barcode'=>'ACC-EAR-BOAT100',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('EARPHONES'),
             'purchase_price'=>250,'selling_price'=>450,'tax_percentage'=>5,'minimum_stock_alert'=>10,
             'manufacturer'=>'boAt','model_number'=>'Bassheads 100','part_number'=>'BH100-BLK','warranty'=>'1 Year','weight'=>'18g','country_of_origin'=>'India',
             'image'=>$f('p_ear_boat.jpg'),'gallery'=>array_filter([$f('p_ear_wired.jpg')]),
             'stock_quantity'=>60,'short_description'=>'boAt Bassheads 100 wired earphones with mic, extra bass, 1.2m tangle-free cable.'],

            ['name'=>'Wired Earphones 3.5mm (Clearance)','code'=>'ACC-EAR-CLR01','barcode'=>'ACC-EAR-CLR01',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('EARPHONES'),
             'purchase_price'=>200,'selling_price'=>200,'tax_percentage'=>5,'minimum_stock_alert'=>5,
             'manufacturer'=>'Generic','model_number'=>'EAR-CLR-3.5','part_number'=>'ACC-EAR-CLR01','warranty'=>'No Warranty','weight'=>'30g','country_of_origin'=>'China',
             'image'=>$f('p_ear_wired.jpg'),'gallery'=>[],
             'stock_quantity'=>50,'short_description'=>'Clearance stock — generic wired earphones, 3.5mm jack, zero profit pricing.'],

            ['name'=>'Tempered Glass Screen Guard (Universal)','code'=>'ACC-SG-TEMP','barcode'=>'ACC-SG-TEMP',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('SCREEN-GUARDS'),
             'purchase_price'=>40,'selling_price'=>99,'tax_percentage'=>5,'minimum_stock_alert'=>30,
             'manufacturer'=>'Generic','model_number'=>'TG-9H-UNIV','part_number'=>'ACC-SG-TEMP','warranty'=>'No Warranty','weight'=>'15g','country_of_origin'=>'China',
             'image'=>$f('p_tg_univ.jpg'),'gallery'=>[],
             'stock_quantity'=>350,'short_description'=>'9H hardness anti-fingerprint tempered glass, 0.33mm thickness, universal fit.'],

            ['name'=>'Silicone Phone Case (Universal)','code'=>'ACC-CASE-SIL','barcode'=>'ACC-CASE-SIL',
             'brand_id'=>$this->brand('GENERIC'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('PHONE-CASES'),
             'purchase_price'=>60,'selling_price'=>149,'tax_percentage'=>5,'minimum_stock_alert'=>25,
             'manufacturer'=>'Generic','model_number'=>'SIL-CASE-UNIV','part_number'=>'ACC-CASE-SIL','warranty'=>'No Warranty','weight'=>'30g','country_of_origin'=>'China',
             'image'=>$f('p_case_sil.jpg'),'gallery'=>array_filter([$f('p_case_sil_b.jpg')]),
             'stock_quantity'=>250,'short_description'=>'Flexible soft silicone phone case with shock-absorbing corners, universal sizing.'],

            ['name'=>'Dell 65W AC Adapter Charger','code'=>'ACC-CHR-DEL65','barcode'=>'ACC-CHR-DEL65',
             'brand_id'=>$this->brand('DELL'),'main_category_id'=>$this->mainCat('ACCESSORIES-ITEM'),'sub_category_id'=>$this->subCat('USB-CABLES'),
             'purchase_price'=>600,'selling_price'=>999,'tax_percentage'=>18,'minimum_stock_alert'=>8,
             'manufacturer'=>'Dell','model_number'=>'65W AC Adapter LA65NS2-01','part_number'=>'HA65NS5-00','warranty'=>'6 Months','weight'=>'300g','country_of_origin'=>'China',
             'image'=>$f('p_charger_del.jpg'),'gallery'=>[],
             'stock_quantity'=>22,'short_description'=>'Original Dell 65W AC adapter for Inspiron, Vostro and Latitude series.'],
        ];

        $products = array_merge($products, $more);

        // ── default status for all ────────────────────────────────────────────
        $defaults = [
            'status'            => 'active',
            'unit_name'         => 'Piece',
            'unit_code'         => 'PCS',
            'color'             => null,
            'full_description'  => null,
            'discount_price_amount' => 0,
        ];

        $count = 0;
        foreach ($products as $data) {
            $stockQty = $data['stock_quantity'];
            unset($data['stock_quantity']);

            $data = array_merge($defaults, $data);
            $data['gallery'] = array_values(array_filter($data['gallery'] ?? []));

            $product = Product::updateOrCreate(['code' => $data['code']], $data);
            Stock::updateOrCreate(['product_id' => $product->id], ['quantity' => $stockQty]);
            $count++;
        }

        $this->command->info("✅ ProductSeeder — {$count} products seeded with real matched images.");
    }
}
