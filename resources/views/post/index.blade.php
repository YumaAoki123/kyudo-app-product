<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>弓道管理Pro</title>

    <link rel="stylesheet" href="{{ asset('css/resultStyle.css') }}">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


</head>

<body>

    <x-app-layout>
        <x-slot name="header">
            <form method="POST" action="{{ route('post.process') }}">
                @csrf


                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="input-daterange input-group" id="datepicker">


                            <div class="input-group-prepend">
                                <span class="input-group-text">開始日</span>
                            </div>
                            <input type="text" class="input-sm form-control" name="from" />


                            <div class="input-group-append">
                                <span class="input-group-text">終了日</span>
                            </div>
                            <input type="text" class="input-sm form-control" name="to" />
                        </div>
                    </div>
                    <button type="submit" class="submit-button">決定</button>
                </div>





            </form>

        </x-slot>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <section>
                        <h1 class="heading-normal">
                            的中データ概要
                        </h1>

                        <div class="container">

                            <div class="row justify-content-center">

                                <div class="col-lg-6 col-md-12">
                                    <div class="target" id="target" style="display: flex; justify-content: center; align-items: center;">

                                        <div class="ring ring-0">

                                            <div class="quadrant" id="quadrant1">
                                                @if(isset($percentageData))
                                                <div class="percentage" id="percentage1">{{ round($percentageData['topLeftPercentage'],1) }}%</div>
                                                @endif
                                            </div>
                                            <div class="quadrant" id="quadrant2">
                                                @if(isset($percentageData))
                                                <div class="percentage" id="percentage2">{{ round($percentageData['topRightPercentage'],1) }}%</div>
                                                @endif
                                            </div>
                                            <div class="quadrant" id="quadrant3">
                                                @if(isset($percentageData))
                                                <div class="percentage" id="percentage3">{{ round($percentageData['bottomLeftPercentage'],1) }}%</div>
                                                @endif
                                            </div>
                                            <div class="quadrant" id="quadrant4">
                                                @if(isset($percentageData))
                                                <div class="percentage" id="percentage4">{{ round($percentageData['bottomRightPercentage'],1) }}%</div>
                                                @endif
                                            </div>

                                        </div>

                                        <div class="ring ring-1"></div>
                                        <div class="ring ring-2"></div>
                                        <div class="ring ring-3"></div>
                                        <div class="ring ring-4"></div>
                                        <div class="ring ring-5"></div>
                                        <div class="ring ring-6"></div>

                                        <!-- 的中したポイントを赤丸で表示 -->
                                        @if(isset($posts) && count($posts) > 0)
                                        @foreach ($posts as $post)
                                        @php
                                        $x = $post->pointX;
                                        $y = $post->pointY;

                                        @endphp
                                        <div class="point" style="top: {{ $y * 100 }}%; left: {{ $x * 100 }}%;"></div>

                                        @endforeach
                                        @endif





                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-12">

                                    <table class="table">
                                        <thead>

                                            <tr>
                                                <th>項目</th>
                                                <th>値</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($posts) && count($posts) > 0)

                                            <tr>
                                                <td>射数</td>
                                                <td> {{$statisticsData['totalCount']}} 射 </td>
                                            </tr>
                                            <tr>
                                                <td>的中数</td>
                                                <td> {{ $statisticsData['hitCount'] }} 回</td>
                                            </tr>
                                            <tr>
                                                <td>的中率</td>
                                                <td>{{ round($statisticsData['accuracy'],1) }} %</td>
                                            </tr>

                                            @endif
                                        </tbody>
                                    </table>
                                </div>



                            </div>
                        </div>
                    </section>


                    <section>



                        <h1 class="heading-normal">
                            詳細データ
                        </h1>

                        <section>
                            <h2 class="heading-small">
                                射順別的中率
                            </h2>
                            <div class="container2">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6 col-md-12">
                                        <canvas id="anotherChart"></canvas>
                                    </div>
                                    <div class="col-lg-6 col-md-12">

                                        <table class="table">
                                            <thead>

                                                <tr>
                                                    <th>項目</th>
                                                    <th>値</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($statisticsData))
                                                @foreach ($statisticsData['firstShotLabels'] as $index => $firstShotLabel)
                                                <tr>
                                                    <td>{{ $firstShotLabel }}</td>
                                                    <td>{{ round($statisticsData['firstShotAccuracies'][$index +1],1) }} %</td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="heading-small">
                                四射的中成績
                            </h2>
                            <div class="container2">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6 col-md-12">
                                        <canvas id="barChart"></canvas>
                                    </div>


                                    <div class="col-lg-6 col-md-12">

                                        <table class="table">
                                            <thead>

                                                <tr>
                                                    <th>項目</th>
                                                    <th>値</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($posts) && count($posts) > 0)
                                                <tr>
                                                    <td>立ち数</td>
                                                    <td>{{ $countData['totalCount'] }} 回</td>
                                                </tr>
                                                @foreach ($countData['countLabels'] as $index => $countLabel)
                                                <tr>
                                                    <td>{{ $countLabel }}</td>
                                                    <td>{{ $countData['countResults'][$countLabel] }} 回</td>
                                                </tr>
                                                @endforeach


                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </section>

                </div>
            </div>
        </div>





        <!-- jQuery, popper.js, Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!-- bootstrap-datepicker -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>

        <!-- bootstrap-datepickerの設定 -->
        <script>
            $('.input-daterange').datepicker({
                    language: 'ja', // 日本語化
                    format: 'yyyy/mm/dd', // 日付表示をyyyy/mm/ddにフォーマット
                })
                .on({
                    changeDate: function() {
                        // datepickerの日付を取得
                        console.log('開始日付 :', $('input[name="from"]').val()); // 開始日付を取得
                        console.log('終了日付 :', $('input[name="to"]').val()); // 終了日付を取得
                    }
                });
        </script>


        @if(isset($countData))
        <script>
            // データの取得
            const labels = @json($countData['countLabels']);
            const data = @json(array_values($countData['countResults']));

            // グラフの描画
            const ctx = document.getElementById('barChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '回数',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // 棒グラフの背景色
                        borderColor: 'rgba(54, 162, 235, 1)', // 棒グラフの枠線の色
                        borderWidth: 1, // 棒グラフの枠線の太さ
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0, // y軸の表示精度（小数点以下の桁数）
                                stepSize: 0, // y軸の目盛りの間隔
                            }
                        }
                    },
                    animation: true,
                }
            });
        </script>
        @endif

        @if(isset($statisticsData))
        <script>
            const labels2 = @json($statisticsData['firstShotLabels']);
            const data2 = @json(array_values($statisticsData['firstShotAccuracies']));
            const ctx2 = document.getElementById('anotherChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: labels2,
                    datasets: [{
                        label: '的中率',
                        data: data2,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 0,
                            }
                        }
                    }
                }
            });
        </script>
        @endif


    </x-app-layout>
</body>

</html>