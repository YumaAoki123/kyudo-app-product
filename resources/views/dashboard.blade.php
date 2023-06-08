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
                        dataType: 'json',
                        data: {
                            selectedDate: dateText,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                // セッション保存成功時の処理
                                window.location.href = "{{route('post.create')}}";
                            } else {
                                console.log('セッション保存エラー');
                                // エラーメッセージを表示するなどの処理を追加
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Ajaxエラー: ' + textStatus + ': ' + errorThrown);
                            // エラーメッセージを表示するなどの処理を追加
                            window.location.href = "{{route('home')}}"
                        }
                    });
                }
            });
        });
    </script>

</head>

<body>
    @php
    $successRate = $statisticsData['accuracy']
    @endphp
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                マイページ
            </h2>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <section>
                        <h1 class="heading-normal">
                            最近の稽古
                        </h1>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-12">
                                    <h1 class="heading-small">
                                        最近の成績
                                    </h1>
                                    <div class="chart-container">
                                        <canvas id="lineChart" class="lineChart"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <h1 class="heading-small">
                                        本日の的中率
                                    </h1>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>項目</th>
                                                <th>値</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>射数</td>
                                                <td> {{$statisticsData['totalCount']?? 0}} 射 </td>
                                            </tr>
                                            <tr>
                                                <td>的中数</td>
                                                <td> {{ $statisticsData['hitCount']?? 0 }} 回</td>
                                            </tr>
                                            <tr>
                                                <td>的中率</td>
                                                <td>{{ round($statisticsData['accuracy']?? 0,1) }} %</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </section>

                    <section>
                        <h1 class="heading-normal">
                            稽古情報
                        </h1>
                        <div class="container">
                            <div class="row">

                                <section>
                                    <h1 class="heading-small">
                                        新規作成
                                    </h1>
                                    <div class="col-lg-6 col-md-12">
                                        <div id="datepicker"></div>
                                    </div>
                                </section>

                                <section>
                                    <h1 class="heading-small">
                                        統計情報
                                    </h1>
                                    <div class="container">
                                        <div class="button-container">
                                            <div class="column">
                                                <a href="{{ route('post.index') }}" class="btn btn-pageChange btn--cubic btn--shadow">統計データ詳細</a>
                                            </div>
                                            <div class="column">
                                                <a href="{{ route('post.dataList') }}" class="btn btn-pageChange btn--cubic btn--shadow" style="margin-left: 14px; margin-top: 40px;">データ一覧</a>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script>
        const dataByDate = @json($dataByDate);
        const dateIdLabels = @json($dateIdLabels);

        const dataByDateSorted = Object.entries(dataByDate).sort(([dateIdA], [dateIdB]) => dateIdA - dateIdB);
        const labels = [];
        const data = [];

        for (const [dateId, dateData] of dataByDateSorted) {
            const dateLabels = [];
            const dateDataPoints = [];

            for (const date in dateData) {
                const posts = dateData[date];
                let dateTotalCount = 0;
                let dateHitCount = 0;

                for (const post of posts) {
                    const x = post.pointX - 0.5;
                    const y = post.pointY - 0.5;

                    if (x * x + y * y <= 0.5 * 0.5) {
                        dateHitCount++; // 的中したポイントをカウント
                    }
                    dateTotalCount++; // ポイントの総数をカウント
                }

                const accuracy = (dateTotalCount > 0) ? (dateHitCount / dateTotalCount) * 100 : 0;
                dateLabels.unshift(dateId);
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
                    label: '最近10立の結果',
                    data: data.map(({
                        dateId,
                        accuracy
                    }) => ({
                        x: dateId,
                        y: accuracy
                    })),
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: "rgb(0, 69, 87)",
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: true,
                        },
                    },
                    y: {
                        max: 105,
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 5,
                            callback: function(value, index, values) {
                                return accuracyLabels[value];
                            }
                        },
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                animation: true,
            }
        });
    </script>

</body>

</html>