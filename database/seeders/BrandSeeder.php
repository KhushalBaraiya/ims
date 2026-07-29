<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BrandSeeder extends Seeder
{
    /** Brand color palette — each brand gets a unique color */
    private array $colors = [
        'APPLE' => ['bg' => '#1d1d1f', 'fg' => '#ffffff'],
        'SAMSUNG' => ['bg' => '#1428a0', 'fg' => '#ffffff'],
        'ASUS' => ['bg' => '#00539b', 'fg' => '#ffffff'],
        'DELL' => ['bg' => '#007db8', 'fg' => '#ffffff'],
        'HP' => ['bg' => '#0096d6', 'fg' => '#ffffff'],
        'LENOVO' => ['bg' => '#e2231a', 'fg' => '#ffffff'],
        'INTEL' => ['bg' => '#0071c5', 'fg' => '#ffffff'],
        'AMD' => ['bg' => '#ed1c24', 'fg' => '#ffffff'],
        'XIAOMI' => ['bg' => '#ff6900', 'fg' => '#ffffff'],
        'ONEPLUS' => ['bg' => '#eb0029', 'fg' => '#ffffff'],
        'REALME' => ['bg' => '#ffd200', 'fg' => '#000000'],
        'OPPO' => ['bg' => '#1d8348', 'fg' => '#ffffff'],
        'VIVO' => ['bg' => '#415fff', 'fg' => '#ffffff'],
        'BOSCH' => ['bg' => '#cc0000', 'fg' => '#ffffff'],
        'SONY' => ['bg' => '#000000', 'fg' => '#ffffff'],
        'LG' => ['bg' => '#a50034', 'fg' => '#ffffff'],
        'NIKE' => ['bg' => '#111111', 'fg' => '#ffffff'],
        'ADIDAS' => ['bg' => '#000000', 'fg' => '#ffffff'],
        'GENERIC' => ['bg' => '#6c757d', 'fg' => '#ffffff'],
    ];

    public function run(): void
    {
        $uploadPath = public_path('uploads/brands');
        File::ensureDirectoryExists($uploadPath);

        $brands = [
            ['name' => 'Apple',   'slug' => 'APPLE',   'description' => 'American multinational technology company.',                      'status' => 'active'],
            ['name' => 'Samsung', 'slug' => 'SAMSUNG', 'description' => 'South Korean multinational electronics corporation.',              'status' => 'active'],
            ['name' => 'Asus',    'slug' => 'ASUS',    'description' => 'Taiwanese multinational computer hardware company.',               'status' => 'active'],
            ['name' => 'Dell',    'slug' => 'DELL',    'description' => 'American technology company known for computers.',                 'status' => 'active'],
            ['name' => 'HP',      'slug' => 'HP',      'description' => 'American multinational information technology company.',           'status' => 'active'],
            ['name' => 'Lenovo',  'slug' => 'LENOVO',  'description' => 'Chinese multinational technology company.',                       'status' => 'active'],
            ['name' => 'Intel',   'slug' => 'INTEL',   'description' => 'American multinational semiconductor corporation.',                'status' => 'active'],
            ['name' => 'AMD',     'slug' => 'AMD',     'description' => 'American multinational semiconductor company.',                    'status' => 'active'],
            ['name' => 'Xiaomi',  'slug' => 'XIAOMI',  'description' => 'Chinese electronics and software company.',                       'status' => 'active'],
            ['name' => 'OnePlus', 'slug' => 'ONEPLUS', 'description' => 'Chinese smartphone manufacturer.',                                'status' => 'active'],
            ['name' => 'Realme',  'slug' => 'REALME',  'description' => 'Chinese smartphone brand.',                                       'status' => 'active'],
            ['name' => 'Oppo',    'slug' => 'OPPO',    'description' => 'Chinese consumer electronics manufacturer.',                      'status' => 'active'],
            ['name' => 'Vivo',    'slug' => 'VIVO',    'description' => 'Chinese technology and smartphone company.',                      'status' => 'active'],
            ['name' => 'Bosch',   'slug' => 'BOSCH',   'description' => 'German multinational engineering and technology company.',        'status' => 'active'],
            ['name' => 'Sony',    'slug' => 'SONY',    'description' => 'Japanese multinational technology and entertainment company.',    'status' => 'active'],
            ['name' => 'LG',      'slug' => 'LG',      'description' => 'South Korean multinational electronics company.',                 'status' => 'active'],
            ['name' => 'Nike',    'slug' => 'NIKE',    'description' => 'American multinational footwear and apparel corporation.',        'status' => 'active'],
            ['name' => 'Adidas',  'slug' => 'ADIDAS',  'description' => 'German multinational sportswear company.',                       'status' => 'active'],
            ['name' => 'Generic', 'slug' => 'GENERIC', 'description' => 'Generic / unbranded products.',                                  'status' => 'active'],
        ];

        foreach ($brands as $b) {
            $imageFile = $this->generateLogoSvg($uploadPath, $b['name'], $b['slug']);

            Brand::firstOrCreate(
                ['slug' => $b['slug']],
                [
                    'name'        => $b['name'],
                    'slug'        => $b['slug'],
                    'description' => $b['description'],
                    'status'      => $b['status'],
                ]
            );
        }

        $this->command->info('✅ BrandSeeder: '.count($brands).' brands seeded with SVG logos.');
    }

    /** Generate a clean SVG logo (initials + brand color) and save as PNG-named file */
    private function generateLogoSvg(string $uploadPath, string $name, string $slug): string
    {
        $colors = $this->colors[$slug] ?? ['bg' => '#696cff', 'fg' => '#ffffff'];
        $bg = $colors['bg'];
        $fg = $colors['fg'];
        $initials = $this->initials($name);
        $filename = 'brand_'.strtolower($slug).'.svg';
        $filePath = $uploadPath.DIRECTORY_SEPARATOR.$filename;

        if (! File::exists($filePath)) {
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">
  <rect width="128" height="128" rx="64" fill="{$bg}"/>
  <text x="64" y="64" dominant-baseline="central" text-anchor="middle"
        font-family="Arial, Helvetica, sans-serif"
        font-size="44" font-weight="700" fill="{$fg}" letter-spacing="1">
    {$initials}
  </text>
</svg>
SVG;
            File::put($filePath, $svg);
        }

        return $filename;
    }

    /** Get 1–2 initials from brand name */
    private function initials(string $name): string
    {
        $words = preg_split('/[\s\-]+/', trim($name));
        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1).mb_substr($words[1], 0, 1));
        }

        return strtoupper(mb_substr($name, 0, 2));
    }
}
