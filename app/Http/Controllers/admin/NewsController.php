<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\NewsRequest as Request;

use App\Models\News;

class NewsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$news = News::orderBy('id', 'desc')->paginate(10);

		return view('admin.news.index', compact('news'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.news.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$news = new News();

		$news->name = $request->input("name");
        $news->sysname = $request->input("sysname");
        $news->date = $request->input("date");
        $news->body = $request->input("body");
        $news->status = $request->input("status");
        $news->title = $request->input("title");
        $news->keywords = $request->input("keywords");
        $news->description = $request->input("description");

        $news->uploads->upload();

		$news->save();

		return redirect()->route('admin.news.index')->with('message', 'Новость создана.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$news = News::findOrFail($id);

		return view('admin.news.show', compact('news'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = News::findOrFail($id);

		return view('admin.news.edit', compact('news'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$news = News::findOrFail($id);

		$news->name = $request->input("name");
        $news->sysname = $request->input("sysname");
        $news->date = $request->input("date");
        $news->body = $request->input("body");
        $news->status = $request->input("status");
        $news->title = $request->input("title");
        $news->keywords = $request->input("keywords");
        $news->description = $request->input("description");

        $news->uploads->upload();

		$news->save();

		return redirect()->route('admin.news.index')->with('message', 'Новость отредактирована.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$news = News::findOrFail($id);
		$news->uploads->delete();
		$news->delete();

		return redirect()->route('admin.news.index')->with('message', 'Новость удалена.');
	}

}
