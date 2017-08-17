<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
/*
 * Операции с текстом продукта
 * **/
class ProductsSex extends Command
{
    /**
     * The name and signature of the console command.
     * @param attributeName from ['sex', 'material']
     * @option removeSex - опция, для включение удаления строк из товаров
     * @var string
     */
    protected $signature = 'products:text {attributeName} {--remove} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows information about the attribute of the product contained in the text, can write in log this information and delete the string with the attribute';

    //
    private $attributes = [
        'sex' => '<strong>Пол:',
        'material' => '<strong>Состав ткани:',
        'p' => '<p></p>',
        'pbr' => '<p><br />'
    ];
    private $patterns = [
        'sex' => '/<strong>Пол:.*nbsp;/',
        'material' => '/<strong>Состав ткани:.*a>/',
        'p'=>'/^<p><\/p>/',
        'pbr' => '/^<p><br \/>\r\n/',
    ];
    private $replacement = [
        'sex' => '',
        'material' => '',
        'p' => '',
        'pbr' => '<p>',
    ];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /*
     * Возвращает количество товаров, в которых найдена заданная подстрока
     * **/
    public function getCount($attribute) {
        $count = Product::where('text', 'like', '%'.$this->attributes[$attribute].'%')->count();
        $this->info('count of products with a specified attribute:'.$count);
    }
    /*
     * пишет в лог строку, в которой встречается подстрока
     * **/
    private function log($attribute) {
        $products = Product::where('text', 'like', '%'.$this->attributes[$attribute].'%')->get();
        foreach($products as $product) {
            Log::info('id:'.$product->id);
            Log::info('name:'.$product->name);
            $pattern = $this->patterns[$attribute];
            $arr = [];
            if(preg_match($pattern, $product->text, $arr)) {
                Log::info($arr[0]);
            }else {
                Log::info('pattern not found!');
            };
        }
        $this->info('strings logged');
    }
    /*
     * удаляет из текста товаров строку, начинающуюся с подстроки
     * **/
    private function remove($attribute) {
        $products = Product::where('text', 'like', '%'.$this->attributes[$attribute].'%')->get();
//        $id = 1;
//        $product = $products->where('id',$id)->first();
        foreach($products as $product) {
            if($product) {
                if($newText = preg_replace($this->patterns[$attribute],$this->replacement[$attribute],$product->text, 1)) {
                    $product->update(['text'=> $newText]);
                }else {
                    Log::info('pattern not found!');
                };
            }
        }
        $this->info('text changed');
    }
    /**
     * Выводит информацию о количестве товаров в тексте которого найден аттрибут пол
     * Удаляет строку с аттрибутом пол из текста товара, если указана опция remove
     * Пишет в лог строку каждого товара, в которой найден аттрибут пол
     * @return mixed
     */
    public function handle()
    {
        $attribute = $this->argument('attributeName');
        //получаем строку, которая соответсвует аргументу
        if(isset($this->attributes[$attribute])) {
            $substring = $this->attributes[$attribute];
        }else {
            $this->info('attribute '.$attribute.' is not supported');
            $this->info('supported attributes:'.implode(',', array_keys($this->attributes)));
            return false;
        }
        //печатаем количество товаров, подходящих под условие
        $this->info('products with '.$attribute.' attribute in text:');
        $this->getCount($attribute);
        //выполняем действия соответствующие заданным флагам
        if($this->option('log')) {
            $this->log($attribute);
        }
        if($this->option('remove')) {
            $this->remove($attribute);
        }
        //печатаем количество товаров, подходящих под условие, после выполнения действий
        if($this->option('remove')) {
            $this->info('products remained with '.$attribute.' attribute in text:');
            $this->getCount($attribute);
        }
    }
}
