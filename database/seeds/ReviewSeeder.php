<?php

use Illuminate\Database\Seeder;

use App\Models\Review;

class ReviewSeeder extends Seeder {

    public function run()
    {
        factory(Review::class, 15)->create();
    }

}
