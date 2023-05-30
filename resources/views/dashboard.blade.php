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
        @php
        $successRate = round($statisticsData['accuracy'],1)
        @endphp

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <section>
                        <h1 class="heading-normal">
                            本日の的中率
                        </h1>
                        <div class="{{
    ($successRate < 50) ? 'low-success-rate' : 
    (($successRate < 80) ? 'medium-success-rate' : 'high-success-rate')
}}">
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
                                                    <th scope="col">項目</th>
                                                    <th scope="col">値</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($posts) && count($posts) > 0)
                                                <tr>

                                                    <td>射数</td>
                                                    <td>{{$statisticsData['totalCount']}}</td>
                                                </tr>
                                                <tr>
                                                    <td>的中回数</td>
                                                    <td>{{ $statisticsData['hitCount'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>的中率</td>
                                                    <td>{{ round($statisticsData['accuracy'],1) }}%</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>


                                    </div>


                                </div>


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

                    </div>
                    </section>

                </div>
            </div>
        </div>

    </x-app-layout>



</body>

</html>