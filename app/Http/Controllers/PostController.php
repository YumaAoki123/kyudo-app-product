<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */


    public function create(Request $request)
    {
        // セッションから日付を取得する
        $selectedDate = $request->session()->get('selected_date');

        // 日付が存在しない場合は、セッションIDを削除してログアウトする
        if (!$selectedDate) {
            $request->session()->invalidate();
            return redirect('/login')->with('error', 'Invalid access. Please login again.');
        }

        // 日付をビューに渡して表示する
        return view('post.create')->with('selectedDate', $selectedDate);
    }

    public function saveSelectedDate(Request $request)
    {
        $selectedDate = $request->input('selectedDate');

        // セッションに日付を保存
        $request->session()->put('selected_date', $selectedDate);

        return response()->json(['success' => true]);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $jsonData = $request->getContent();
            $data = json_decode($jsonData, true);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }


        // データを確認
        dd($request->all());

        // セッションから日付を取得する
        // $selectedDate = $request->session()->get('selected_date');
        // $dateId = $request->input('date_id');

        // $post = new Post;
        // $post->pointX = $request->input('pointX');
        // $post->pointY = $request->input('pointY');
        // $post->pointNumber = $request->input('pointNumber');
        // $post->shotCount = $request->input('shotCount');
        // $post->date_id = $dateId;
        // $post->save();

        // $date = new Date();
        // $date->date = $selectedDate;
        // $date->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
