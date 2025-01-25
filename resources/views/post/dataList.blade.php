<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>弓道管理Pro</title>
    <link rel="stylesheet" href="{{ asset('css/dataListStyle.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>

    <x-app-layout>
        <x-slot name="header">
            <form method="GET" action="{{ route('post.showDataList') }}">

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
                </div>
                <button type="submit" class="submit-button">決定</button>
            </form>

            @if(session('false'))
            <div class="alert alert-danger">
                {{ session('false') }}
            </div>
            @endif
        </x-slot>

        @if(isset($dataByDate))
        @foreach ($dataByDate as $date => $dateData)
        <h2 class="heading-normal">{{ $date }}</h2>

        @foreach ($dateData as $dateId => $posts)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container" id="container-{{$dateId}}">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-12">
                            <div class="target" id="target" style="display: flex; justify-content: center; align-items: center;">
                                <div class="circle circle-0"></div>
                                <div class="circle circle-1"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                                <div class="circle circle-4"></div>
                                <div class="circle circle-5"></div>
                                <div class="circle circle-6"></div>
                                <!-- 的中したポイントを赤丸で表示 -->
                                @foreach ($posts as $post)
                                @php
                                $x = $post->pointX;
                                $y = $post->pointY;
                                @endphp
                                <div class="point" style="top: {{ $y * 100 }}%; left: {{ $x * 100 }}%;"></div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <table class="table">
                                <tr>
                                    @for ($i = 1; $i <= 4; $i++)<th value='$i'>{{$i}}</th>@endfor
                                </tr>
                                <tr>
                                <tr>
                                    @foreach ($posts as $post)
                                    <td>
                                        @php
                                        $x = $post->pointX - 0.5;
                                        $y = $post->pointY - 0.5;
                                        $isHit = ($x * $x + $y * $y <= 0.5 * 0.5); @endphp @if ($isHit) &#9675; @else &#10005; @endif </td>

                                            @endforeach
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <form id="deleteForm{{ $dateId }}" action="{{ route('post.destroy', $dateId) }}" method="post">

                                @method('DELETE')
                            </form>
                        </div>
                        <button type="button" class="delete-button" data-id="{{ $dateId }}">削除</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endforeach
        @endif


        <div>
            @if(isset($dates) && $dates->isNotEmpty())
            {{ $dates->appends(Request::query())->links() }}
            @else
            <p>データがありません。</p>
            @endif
        </div>



        <!-- jQuery, popper.js, Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!-- bootstrap-datepicker -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>

        <!-- bootstrap-datepickerの設定 -->
        <script>
            $('.input-daterange').datepicker({
                    language: 'ja',
                    format: 'yyyy/mm/dd',
                })
                .on({
                    changeDate: function() {
                        // datepickerの日付を取得
                        console.log('開始日付 :', $('input[name="from"]').val());
                        console.log('終了日付 :', $('input[name="to"]').val());
                    }
                });
        </script>

        <script>
            $(document).ready(function() {
                $('.delete-button').on('click', function() {
                    var deleteButton = $(this);
                    var dataId = deleteButton.data('id');
                    if (confirm('本当に削除しますか？')) {
                        $.ajax({
                            url: "{{ route('post.destroy', '') }}" + '/' + dataId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {


                                    // 削除が成功した場合の処理
                                    $('#container-' + dataId).remove();

                                } else {
                                    // エラーが発生した場合の処理
                                    alert(response.message);
                                }
                            },
                        });
                    }
                });
            });
        </script>

    </x-app-layout>
</body>

</html>