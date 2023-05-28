<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Date;
use Illuminate\Support\Facades\DB;
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

        return view('dashboard', [
            'posts' => $posts,
            'statisticsData' => $statisticsData,
        ]);
    }
}
