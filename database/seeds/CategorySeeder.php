<?php

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $man   = factory(Category::class)->create([
            'parent_id' => 0,
            'name'      => 'Для мужчин',
            'sysname'   => 'man',
            'status'    => 1
        ]);

        $woman = factory(Category::class)->create([
            'parent_id' => 0,
            'name'      => 'Для женщин',
            'sysname'   => 'woman',
            'status'    => 1
        ]);

        $roots = collect([$man, $woman]);

        $subCategories = [];
        foreach($roots as $category)
            foreach(['Одежда', 'Аксессуары'] as $name)
                $subCategories[] = factory(Category::class)->create([
                    'parent_id' => $category->id,
                    'name'      => $name,
                    'status'    => 1,
                ]);

        for($i = 0; $i < 30; $i++)
            factory(Category::class)->create(['parent_id' => collect($subCategories)->random()['id']]);
    }
}
