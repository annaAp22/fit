<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Console\Command;

class Phone extends Command
{
    /**
     * Replace phone nubmer in categories and tags description
     *
     * @var string
     */
    protected $signature = 'command:change-phone {search} {replace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace phone nubmer in categories and tags description on other number';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /***/
    public function replaceDescription($models, $search, $replace) {
        foreach ($models as $model) {
            $descritpion = str_replace($search, $replace, $model->description);
            $model->update(['description' => $descritpion]);
        }
        if(!$models) {
            $this->info('string '.$search.' not found');
        }else {
            $this->info('was replaced '.count($models).' descriptions');
        }
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $search = $this->argument('search');
        $replace = $this->argument('replace');
        if($search == $replace) {
            $this->info('nothing to replace - search string equal replace string!');
        }
        $tags = Tag::where('description', 'like', '%'.$search.'%')->get();
        $categories = Category::where('description', 'like', '%'.$search.'%')->get();
        $this->info('tags:');
        $this->replaceDescription($tags, $search, $replace);
        $this->info('categories:');
        $this->replaceDescription($categories, $search, $replace);
    }
}
