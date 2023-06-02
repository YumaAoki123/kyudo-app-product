<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>弓道管理Pro</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dashboardStyle.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function() {
            // カレンダーを表示する
            $("#datepicker").datepicker({
                onSelect: function(dateText, inst) {
                    // セッションに選択された日付を保存する
                    $.ajax({
                        type: "POST",
                        url: "{{route('post.saveSelectedDate')}}",
                        dateFormat: 'yy-mm-dd',
                        data: {
                            selectedDate: dateText,
                            _token: "{{ csrf_token() }}"
                        }
                    }).done(function() {
                        // フォームにリダイレクトする
                        window.location.href = "{{route('post.create')}}";
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + errorThrown);
                    });
                }
            });
        });
    </script>

</head>

<body>

    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                マイページ
            </h2>
        </x-slot>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <section>
                        <h1 class="heading-normal">
                            本日の的中率
                        </h1>

                        <div class="container">


                            <div class="row justify-content-center">

                                <div class="col-lg-6 col-md-12">

                                    <div class="target" id="target">

                                        <div class="ring ring-1"></div>
                                        <div class="ring ring-2"></div>
                                        <div class="ring ring-3"></div>
                                        <div class="ring ring-4"></div>
                                        <div class="ring ring-5"></div>
                                        <div class="ring ring-6"></div>







                                    </div>
                                </div>


                                @if(isset($dataByDate))
                                @foreach ($dataByDate as $date => $dateData)
                                <h3>Date: {{ $date }}</h3>
                                <ul>
                                    @foreach ($dateData as $dateId => $posts)
                                    @php
                                    $totalCount = count($posts);
                                    $hitCount = 0;
                                    foreach ($posts as $post) {
                                    $x = $post->pointX - 0.5;
                                    $y = $post->pointY - 0.5;
                                    if ($x * $x + $y * $y <= 0.5 * 0.5) { $hitCount++; } } $accuracy=($totalCount> 0) ? ($hitCount / $totalCount) * 100 : 0;
                                        @endphp
                                        <li>date_id: {{ $dateId }} - 的中率: {{ $accuracy }}%</li>
                                        @endforeach
                                </ul>
                                @endforeach

                                @endif

                            </div>


                        </div>

                    </section>



                    <h1 class="heading-normal">
                        新規作成
                    </h1>
                    <div class="container">

                        <section>

                            <div class="col-lg-6 col-md-12">

                                <div id="datepicker"></div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <a href="{{ route('post.dataList') }}" class="btn btn-primary btn-custom">データの編集と削除</a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="{{ route('post.index') }}" class="btn btn-primary btn-custom">詳細を表示</a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <canvas id="lineChart" class="lineChart"></canvas>
                            </div>
                    </div>

                    </section>

                </div>
            </div>
        </div>

    </x-app-layout>


    <script>
        const dataByDate = @json($dataByDate);

        const dataByDateSorted = Object.entries(dataByDate).sort(([dateIdA], [dateIdB]) => dateIdA - dateIdB);
        const labels = [];
        const data = [];

        for (const [dateId, dateData] of dataByDateSorted) {
            const dateLabels = [];
            const dateDataPoints = [];

            for (const date in dateData) {
                const posts = dateData[date];
                let dateTotalCount = 0; // date_idごとの総数
                let dateHitCount = 0; // date_idごとの的中数

                for (const post of posts) {
                    const x = post.pointX - 0.5;
                    const y = post.pointY - 0.5;

                    if (x * x + y * y <= 0.5 * 0.5) {
                        dateHitCount++; // 的中したポイントをカウント
                    }
                    dateTotalCount++; // ポイントの総数をカウント
                }

                const accuracy = (dateTotalCount > 0) ? (dateHitCount / dateTotalCount) * 100 : 0;
                dateLabels.unshift(date);
                dateDataPoints.unshift({
                    dateId,
                    accuracy
                });
            }

            labels.unshift(...dateLabels.reverse());
            data.unshift(...dateDataPoints.reverse());


        }
        const accuracyLabels = {
            100: '皆中',
            75: '三中',
            50: '羽分',
            25: '一中',
            0: '残念'
        };


        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '直近十立ちの成績',
                    data: data.map(({
                        accuracy
                    }) => accuracy),
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 5,
                            callback: function(value, index, values) {
                                return accuracyLabels[value];
                            }
                        }
                    }
                },

                animation: true
            }
        });
    </script>

</body>

</html>