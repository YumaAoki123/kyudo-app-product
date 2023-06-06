<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Date;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function index()
    {
        // dd($request->all());
        $posts = []; // $posts変数を初期化

        // // 今週の日付範囲を取得
        // $startOfWeek = Carbon::now()->startOfWeek();
        // $endOfWeek = Carbon::now()->endOfWeek();

        // $userId = auth()->user()->id;

        // $posts = Post::whereHas('date', function ($query) use ($startOfWeek, $endOfWeek, $userId) {
        //     $query->where('user_id', $userId)
        //         ->whereBetween('selectedDate', [$startOfWeek, $endOfWeek]);
        // })->get();

        $today = Carbon::today();

        $userId = auth()->user()->id;

        $posts = Post::whereHas('date', function ($query) use ($today, $userId) {
            $query->where('user_id', $userId)
                ->whereDate('selectedDate', $today);
        })->get();

        // 統計データの表示内容の計算式
        $totalCount = count($posts);
        $hitCount = 0;
        $missCount = 0;
        //直径を1にそろえていて、円の中心を(0,0)とするために0.5引く。
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

        $userId = auth()->user()->id;
        $today = Carbon::today();

        $dates = Date::with(['posts' => function ($query) use ($userId, $today) {
            $query->whereHas('date', function ($query) use ($userId, $today) {
                $query->where('user_id', $userId)
                    ->orderBy('date_id', 'desc') // postsテーブルのカラムであることを確認する必要があります
                    ->where('SelectedDate', '<=', $today);
            })
                ->where('shotCount', 4)
                ->orderBy('date_id', 'desc');
        }])
            ->where('user_id', $userId)
            ->where('SelectedDate', '<=', $today)
            ->orderBy('SelectedDate', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $dataByDate = [];
        foreach ($dates as $date) {
            foreach ($date->posts as $post) {
                $dataByDate[$date->SelectedDate][$post->date_id][] = $post;
            }
        }
        return view('dashboard', [
            'dataByDate' => $dataByDate,
            'posts' => $posts,
            'statisticsData' => $statisticsData,
        ]);
    }
}
