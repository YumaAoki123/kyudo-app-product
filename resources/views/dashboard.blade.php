<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // カレンダーを表示する
            $("#datepicker").datepicker({
                onSelect: function(dateText, inst) {
                    // セッションに選択された日付を保存する
                    $.ajax({
                        type: "POST",
                        url: "/kyudo-app-product/public/saveSelectedDate",
                        dateFormat: 'yy-mm-dd',
                        data: {
                            selectedDate: dateText,
                            _token: "{{ csrf_token() }}"
                        }
                    }).done(function() {
                        // フォームにリダイレクトする
                        window.location.href = "/kyudo-app-product/public/post/create";
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
                    <!-- 今週の的中率を表示するコーナーをここに追加 -->
                    <p>今週の的中率</p>
                    <br>
                    <br>
                </div>

            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- 日付を選択するカレンダー -->
                    <div id="datepicker"></div>
                </div>
            </div>
        </div>


    </x-app-layout>

</body>

</html>