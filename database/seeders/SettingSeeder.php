<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name'    => 'Kalathiya POS',
            'company_email'   => 'admin@kalathiyapos.com',
            'company_phone'   => '+91 98765 43210',
            'company_address' => 'Shop No. 5, Electronics Market, Surat, Gujarat - 395003',
            'currency_code'   => 'INR',
            'currency_symbol' => '₹',
            'tax_name'        => 'GST',
            'tax_percentage'  => '18',
            'invoice_prefix'  => 'INV',
            'purchase_prefix' => 'PO',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
