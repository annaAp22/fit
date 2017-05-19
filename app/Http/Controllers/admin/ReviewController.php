<?php 

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Review;

class ReviewController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$reviews = Review::orderBy('id', 'desc')->paginate(10);

		return view('admin.reviews.index', compact('reviews'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.reviews.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$review = new Review();

		$review->name = $request->input("name");
        $review->message = $request->input("message");
        $review->status = $request->input("status");

		$review->save();

		return redirect()->route('admin.reviews.index')->with('message', 'Отзыв создан.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$review = Review::findOrFail($id);

		return view('admin.reviews.show', compact('review'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$review = Review::findOrFail($id);

		return view('admin.reviews.edit', compact('review'));
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
		$review = Review::findOrFail($id);

		$review->name = $request->input("name");
        $review->message = $request->input("message");
        $review->status = $request->input("status");

		$review->save();

		return redirect()->route('admin.reviews.index')->with('message', 'Отзыв отредактирован.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$review = Review::findOrFail($id);
		$review->delete();

		return redirect()->route('admin.reviews.index')->with('message', 'Отзыв удалён.');
	}

}
