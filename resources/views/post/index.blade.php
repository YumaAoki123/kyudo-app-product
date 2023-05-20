<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>統計情報</title>

    <link rel="stylesheet" href="{{ asset('css/resultStyle.css') }}">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


</head>


</head>

<body>

    <x-app-layout>
        <x-slot name="header">
            <form method="POST" action="{{ route('post.process') }}">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-6">

                            <div class="input-daterange input-group" id="datepicker">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">開始日付</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="from" />
                                <div class="input-group-append">
                                    <span class="input-group-text">終了日付</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="to" />
                            </div>

                        </div>
                    </div>

                </div>

                <input type="submit" value="送信">
            </form>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="target" id="target" style="display: flex; justify-content: center; align-items: center;">

                                    <div class="ring ring-0"></div>
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
                                    <div class="point" style="top: {{ $y }}px; left: {{ $x }}px;"></div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>


                        <div class="row justify-content-center">
                            <div class="col-6">

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
                                            <td>射撃回数</td>
                                            <td> {{$statisticsData['totalCount']}} </td>
                                        </tr>
                                        <tr>
                                            <td>的中回数</td>
                                            <td> {{ $statisticsData['hitCount'] }} </td>
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

    </x-app-layout>
</body>

</html>