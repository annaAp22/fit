<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Article;

class ArticlesWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'category_id' => 0,
        'tag_id' => 0
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(!$this->config['category_id'] && !$this->config['tag_id']) {
            $articles = Article::where('status', 1)->orderBy('created_at', 'desc')->take(9)->get();
        } else {
            //статьи в категории
            if($this->config['category_id']) {
                $articles = Article::whereHas('categories', function ($query) {
                    $query->where('category_id', $this->config['category_id']);
                })->where('status', 1)->take(9)->get();
            //статьи у тега
            } else {
                $articles = Article::whereHas('tags', function ($query) {
                    $query->where('tag_id', $this->config['tag_id']);
                })->where('status', 1)->take(9)->get();
            }
        }

        if(!$articles->count()) {
            return '';
        }

        return view("widgets.articles_widget", [
            'config' => $this->config, 'articles' => $articles
        ]);
    }
}