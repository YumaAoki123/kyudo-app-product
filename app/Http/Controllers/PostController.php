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
    public function index(Request $request)
    {
        return view('post.index');
    }


    public function getPostData(Request $request)
    {
        // dd($request->all());
        $posts = []; // $posts変数を初期化



        if ($request->has('from') && $request->has('to')) {
            $start_date = $request->input('from');
            $end_date = $request->input('to');

            $userId = auth()->user()->id; // ログインユーザのIDを取得するなど、適切な方法でユーザIDを取得してください

            // 日付テーブルと的中データテーブルのリレーションを考慮してデータを取得
            $posts = Post::whereHas('date', function ($query) use ($start_date, $end_date, $userId) {
                $query->where('user_id', $userId)
                    ->whereBetween('selectedDate', [$start_date, $end_date]);
            })->get();
        } else {
            $posts = []; // データが存在しない場合に空の配列として初期化
        }


        // 統計データの表示内容の計算式
        $totalCount = count($posts);
        $hitCount = 0;
        $missCount = 0;
        //直径がを1にそろえているため0.5を引く。
        foreach ($posts as $post) {
            $x = $post->pointX - 0.5;
            $y = $post->pointY - 0.5;

            //円の公式以内なら的中している。
            if ($x * $x + $y * $y <= 0.5 * 0.5) {
                $hitCount++; // 的中したポイントをカウント
            } else {
                $missCount++; // 外れたポイントをカウント
            }
        }

        $accuracy = ($totalCount > 0) ? ($hitCount / $totalCount) * 100 : 0;

        $statisticsData = [
            'totalCount' => $totalCount,
            'hitCount' => $hitCount,
            'missCount' => $missCount,
            'accuracy' => $accuracy,
        ];

        return view('post.index', [
            'posts' => $posts,
            'statisticsData' => $statisticsData
        ]);
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

            // return response()->json(['success' => true]);
            return redirect()->route('post.result');
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()]);
            // エラーメッセージなどを設定してリダイレクト
            // return redirect()->back()->with('error', '保存に失敗しました。もう一度お試しください。');
        }
        // dd('Save complete');
    }


    public function showResult(Request $request)
    {

        return view('post.result');
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
