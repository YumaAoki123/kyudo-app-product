<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Date;
use Illuminate\Support\Facades\DB; // 追加
use Carbon\Carbon;


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
        $selectedDate = $request->session()->get('selected_date');

        // try {
        //     $jsonData = $request->getContent();
        //     $data = json_decode($jsonData, true);

        //     return response()->json(['success' => true]);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e->getMessage()]);
        // }




        try {
            DB::beginTransaction();

            $jsonData = $request->getContent();
            $data = json_decode($jsonData, true);

            $user = User::find(auth()->id());
            $date = new Date();
            $date->selectedDate = Carbon::createFromFormat('m/d/Y', $selectedDate)->format('Y-m-d');

            $user->dates()->save($date);

            foreach ($data as $item) {
                $post = new Post;
                $post->pointX = $item['x'];
                $post->pointY = $item['y'];
                $post->pointNumber = $item['pointNumber'];
                $post->shotCount = $item['shotCount'];
                $post->date_id = $date->id;
                $post->save();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()]);
        }
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
