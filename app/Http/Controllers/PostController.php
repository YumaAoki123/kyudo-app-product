<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Date;
use Illuminate\Support\Facades\DB;
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

    public function dataList(Request $request)
    {
        return view('post.dataList');
    }

    public function result()
    {
        return view('post.result');
    }

    public function showDataList(Request $request)
    {

        if ($request->has('from') && $request->has('to')) {
            $start_date = $request->input('from');
            $end_date = $request->input('to');
            $userId = auth()->user()->id;

            $dates = Date::with(['posts' => function ($query) use ($userId, $start_date, $end_date) {
                $query->whereHas('date', function ($query) use ($userId, $start_date, $end_date) {
                    $query->where('user_id', $userId)
                        ->whereBetween('SelectedDate', [$start_date, $end_date]);
                });
            }])
                ->where('user_id', $userId)
                ->whereBetween('SelectedDate', [$start_date, $end_date])
                ->orderBy('SelectedDate', 'asc')
                ->get();

            $dataByDate = [];
            foreach ($dates as $date) {
                foreach ($date->posts as $post) {
                    $dataByDate[$date->SelectedDate][$post->date_id][] = $post;
                }
            }

            return view('post.dataList', [
                'dataByDate' => $dataByDate,
            ]);
        } else {
            return view('post.dataList', [
                'dataByDate' => [],
            ]);
        }
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


        $firstShotTotalCount = $posts->where('pointNumber', 1)->count();
        $firstShotHitCount = 0; // pointNumberが1で的中したデータの数
        $firstShotMissCount = 0;

        $firstShotPosts = $posts->where('pointNumber', 1);

        foreach ($firstShotPosts as $firstShotPost) {
            $x = $firstShotPost->pointX - 0.5;
            $y = $firstShotPost->pointY - 0.5;
            //円の公式以内なら的中している。
            if ($x * $x + $y * $y <= 0.5 * 0.5) {
                $firstShotHitCount++; // 的中したポイントをカウント
            } else {
                $firstShotMissCount++; // 外れたポイントをカウント
            }
        }

        $firstShotAccuracy = ($firstShotTotalCount > 0) ? ($firstShotHitCount / $firstShotTotalCount) * 100 : 0; // 的中率

        $statisticsData = [
            'totalCount' => $totalCount,
            'hitCount' => $hitCount,
            'missCount' => $missCount,
            'accuracy' => $accuracy,

            'firstShotTotalCount' => $firstShotTotalCount,
            'firstShotHitCount' => $firstShotHitCount,
            'firstShotMissCount' =>  $firstShotMissCount,
            'firstShotAccuracy' => $firstShotAccuracy
        ];



        $dateStatistics = [];

        foreach ($posts as $post) {
            $dateId = $post->date_id;

            if (!isset($dateStatistics[$dateId])) {
                // 初回のデータなら初期化
                $dateStatistics[$dateId] = [
                    'totalShots' => 0,
                    'totalHits' => 0,
                ];
            }

            $dateStatistics[$dateId]['totalShots']++; // 射撃回数をカウント

            $x = $post->pointX - 0.5;
            $y = $post->pointY - 0.5;

            // 円の公式以内なら的中している。
            if ($x * $x + $y * $y <= 0.5 * 0.5) {
                $dateStatistics[$dateId]['totalHits']++; // 的中回数をカウント
            }
        }

        $results = [];

        foreach ($dateStatistics as $dateId => $statistics) {
            $totalShots = $statistics['totalShots'];
            $totalHits = $statistics['totalHits'];

            $countAccuracy = ($totalShots > 0) ? $totalHits / $totalShots : 0;

            $result = $totalHits . '/' . $totalShots;

            $results[] = [
                'dateId' => $dateId,
                'countAccuracy' => $countAccuracy,
                'result' => $result,
            ];
        }

        $countTotalCount = count($results);


        $targetAccuracies = ['1', '0.75', '0.5', '0.25', '0'];
        $countLabels = ['皆中', '三中', '羽分', '一中', '残念'];
        $countResults = [];

        foreach ($targetAccuracies as $index => $targetAccuracy) {
            $filteredResults = array_filter($results, function ($countAccuracy) use ($targetAccuracy) {
                return $countAccuracy['countAccuracy'] == $targetAccuracy;
            });

            $count = count($filteredResults);
            $countResults[$countLabels[$index]] = $count;
        }
        $countData = [
            'countResults' => $countResults,
            'totalCount' => $countTotalCount,
            'countLabels' => $countLabels,
        ];



        //4象限の的中確率
        $topLeftCount = 0;
        $topRightCount = 0;
        $bottomLeftCount = 0;
        $bottomRightCount = 0;

        foreach ($posts as $post) {
            $x = $post->pointX; // データのx座標
            $y = $post->pointY; // データのy座標

            if ($x <= 0.5 && $y <= 0.5) {
                $topLeftCount++;
            } elseif ($x > 0.5 && $y <= 0.5) {
                $topRightCount++;
            } elseif ($x <= 0.5 && $y > 0.5) {
                $bottomLeftCount++;
            } elseif ($x > 0.5 && $y > 0.5) {
                $bottomRightCount++;
            }
        }
        $percentageData = [
            'topLeftPercentage' => ($topLeftCount / $totalCount) * 100,
            'topRightPercentage' => ($topRightCount / $totalCount) * 100,
            'bottomLeftPercentage' => ($bottomLeftCount / $totalCount) * 100,
            'bottomRightPercentage' => ($bottomRightCount / $totalCount) * 100
        ];


        return view('post.index', [
            'posts' => $posts,
            'statisticsData' => $statisticsData,
            'percentageData' => $percentageData,
            'countData' => $countData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function saveSelectedDate(Request $request)
    {
        $selectedDate = $request->input('selectedDate');

        // セッションに日付を保存
        $request->session()->put('selected_date', $selectedDate);


        return response()->json(['success' => true]);
    }

    public function create(Request $request)
    {
        // セッションから日付を取得する
        $selectedDate = $request->session()->get('selected_date');
        $selectedDate = Carbon::createFromFormat('m/d/Y', $selectedDate)->format('Y年m月d日');
        // 日付が存在しない場合は、セッションIDを削除してログアウトする
        if (!$selectedDate) {
            $request->session()->invalidate();
            return redirect('/login')->with('error', 'Invalid access. Please login again.');
        }

        // 日付をビューに渡して表示する
        return view('post.create')->with('selectedDate', $selectedDate);
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
            session()->flash('success', 'データを保存しました。');
            return response()->json([
                'redirect_url' => route('post.create')
            ]);
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
    public function destroy($dateId)
    {
        try {
            DB::beginTransaction();
            // 該当のdate_idに紐づくpostsデータを削除
            Post::where('date_id', $dateId)->delete();

            // 該当のdate_idに紐づくdatesデータを削除
            Date::where('id', $dateId)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        // 削除が完了したらリダイレクトなど適切なレスポンスを返す
        return redirect()->back()->with('success', 'データの削除が完了しました');
    }
}
