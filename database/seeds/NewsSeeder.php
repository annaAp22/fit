<?php

use Illuminate\Database\Seeder;

use App\Models\News;

class NewsSeeder extends Seeder {

    public function run()
    {
        factory(News::class, 10)->create();
    }

}
