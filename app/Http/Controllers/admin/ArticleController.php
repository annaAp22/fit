<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

use Carbon\Carbon;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Article());
        $filters = $this->getFormFilter($request->input());

        $articles = Article::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['name'])) {
            $articles->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        if (!empty($filters) && !empty($filters['sysname'])) {
            $articles->where('sysname', 'LIKE', '%'.$filters['sysname'].'%');
        }
        if (!empty($filters) && !empty($filters['id_category'])) {
            $articles->whereHas('categories', function ($query) use ($filters) {
                $query->where('category_id', $filters['id_category']);
            });
        }
        if (!empty($filters) && !empty($filters['tag'])) {
            $articles->whereHas('tags', function ($query) use ($filters) {
                $query->where('tag_id', $filters['tag']);
            });
        }
        if (!empty($filters) && !empty($filters['date_from'])) {
            $articles->where('date', '>=', (new Carbon($filters['date_from']))->format('Y.m.d'));
        }
        if (!empty($filters) && !empty($filters['date_to'])) {
            $articles->where('date', '<=', (new Carbon($filters['date_to']))->format('Y.m.d'));
        }

        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $articles->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $articles->withTrashed();
        }
        $articles = $articles->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        $categories = Category::where(function($query){
            $query->where('parent_id', 0)
                ->orWhere('parent_id', null);
        })->orderBy('sort')->get();
        $tags = Tag::orderBy('views', 'desc')->orderBy('name')->get();

        return view('admin.articles.index', ['articles' => $articles, 'categories' => $categories, 'tags' => $tags, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $categories = Category::where(function($query){
            $query->where('parent_id', 0)
                ->orWhere('parent_id', null);
        })->orderBy('sort')->get();
        return view('admin.articles.create', ['categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\ArticleRequest $request)
    {
        $data = $request->all();
        $data['date'] = (new Carbon($data['date']))->format('Y.m.d');
        $article = new Article($data);
        $article->uploads->upload();
        $article->save();

        if($request->has('tags'))
            $article->tags()->sync($request->input('tags'));

        if($request->has('categories'))
            $article->categories()->sync($request->input('categories'));

        return redirect()->route('admin.articles.index')->withMessage('Статья добавлена');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::where(function($query){
            $query->where('parent_id', 0)
                ->orWhere('parent_id', null);
        })->orderBy('sort')->get();
        $tags = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        return view('admin.articles.edit', ['article' => $article, 'categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\ArticleRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $data = $request->all();
        $data['date'] = (new Carbon($data['date']))->format('Y.m.d');
        $article->uploads->upload();
        $article->update($data);
        //связь с тегами
        if($request->has('tags'))
            $article->tags()->sync($request->input('tags'));
        elseif($article->tags()->count())
            foreach($article->tags as $tag)
                $article->tags()->detach($tag->id);

        //связь с категориями
        if($request->has('categories'))
            $article->categories()->sync($request->input('categories'));
        elseif($article->categories()->count())
            foreach($article->categories as $category)
                $article->categories()->detach($category->id);
            
        return redirect()->route('admin.articles.index')->withMessage('Статья изменена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::destroy($id);
        return redirect()->route('admin.articles.index')->withMessage('Статья удалена');
    }


    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Article::withTrashed()->find($id)->restore();
        return redirect()->route('admin.articles.index')->withMessage('Статья востановлена');
    }

}
