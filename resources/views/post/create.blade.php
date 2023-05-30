<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>弓道管理Pro</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <p>日付: {{ $selectedDate }}</p>

            </h2>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h1 class="heading-normal">
                        的中データ入力
                    </h1>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-12">
                                <div class="target" id="target">
                                    <div class="ring ring-0"></div>
                                    <div class="ring ring-1"></div>
                                    <div class="ring ring-2"></div>
                                    <div class="ring ring-3"></div>
                                    <div class="ring ring-4"></div>
                                    <div class="ring ring-5"></div>
                                    <div class="ring ring-6"></div>

                                </div>
                            </div>
                        </div>





                        <br>
                        <br>
                        <br>


                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-12" style="display:flex;">
                                <form action="{{ route('post.store') }}" method="POST">

                                    @csrf

                                    <div class="shot-count">
                                        回数:
                                        <select id="shotCountSelect" name="shotCount">

                                            <?php
                                            for ($i = 0; $i <= 4; $i++) {
                                                echo "<option value='$i'>$i</option>";
                                            }
                                            ?>
                                        </select>
                                        射







                                    </div>
                                </form>
                                <button id="clear-button" class="btn btn-clear btn--cubic btn--shadow">クリア</button>
                                <button type="submit" id="submitButton" name="submitButton" class="btn btn-submit btn--cubic btn--shadow"> 決定 </button>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>


    </x-app-layout>



    <!-- jQuery, popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- bootstrap-datepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>

    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // ルート名をデータ属性として設定
        const postStoreRoute = "{{ route('post.store') }}";

        console.log("CSRF Token: ", "{{ csrf_token() }}");
    </script>
</body>

</html>