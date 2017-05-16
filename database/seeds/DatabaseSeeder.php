<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Base
        $this->call(UserGroupSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(SettingSeeder::class);

        // Content
        $this->call(PageSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ReviewSeeder::class);

        // E-Commerce
        $this->call(AttributeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(KitSeeder::class);

        // Customer Area
        $this->call(OrderSeeder::class);
    }
}
