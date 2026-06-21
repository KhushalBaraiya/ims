<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            DemoSeeder::class,
            MainCategorySeeder::class,
            SubCategorySeeder::class,
            SubInCategorySeeder::class,
            BrandSeeder::class,
            CategoryImageSeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
            ContactUsSeeder::class,
            CouponSeeder::class,
            FaqSeeder::class,
            SubFaqSeeder::class,
            OfferSeeder::class,
            CartItemSeeder::class,
            WishListSeeder::class,
            ReviewSeeder::class,
            TermConditionSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            UserAddressSeeder::class,
            StockSeeder::class,
            ReturnExchangePolicySeeder::class,
            ReturnOrderSeeder::class,
            PaymentSeeder::class,
            BlogCategorySeeder::class,
            BlogSeeder::class,
            ProductServiceSeeder::class,
            ProductShippingSeeder::class,
            SaveCardSeeder::class,
            DeleteAccountSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
