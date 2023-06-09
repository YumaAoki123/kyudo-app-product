<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>弓道管理Pro
    </title>
    <link rel="preload" href="images/homeImage.jpg" as="image">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@100;400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        @media (min-width: 768px) {
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: "M PLUS Rounded 1c", sans-serif;
            }

            body {
                height: 100vh;
                width: 100%;
                background-image: url("{{ asset('images/homeImage.jpg') }}");
                background-size: cover;
                background-position: center;
            }

            body::after {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: black;
                opacity: 0.5;
                z-index: -1;
            }


            main {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                white-space: nowrap;
                text-align: center;
                color: rgb(255, 255, 255);
            }

            main h1 {
                margin-bottom: 15px;
                font-size: 65px;
            }

            main p {
                font-size: 30px;
                font-weight: 600;
            }

            main .button-area {
                margin-top: 40px;
            }

            .button-area a {
                padding: 17px 30px;
                background: #da9168;
                border: none;
                border-radius: 30px;
                color: rgb(0, 0, 0);
                font-weight: 600;
                font-size: 17px;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
            }

            .button-area a {
                margin-right: 30px;
            }

            .button-area a:hover {
                color: #767685;
            }

        }

        @media (max-width: 767px) {
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: "M PLUS Rounded 1c", sans-serif;
            }

            body {
                height: 100vh;
                width: 100%;
                background-image: url("{{ asset('images/homeImage.jpg') }}");
                background-size: cover;
                background-position: center;
            }


            body::after {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background: black;
                opacity: 0.5;
                z-index: -1;
            }

            header {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-evenly;
                padding: 5px 20px;
            }



            main {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                white-space: nowrap;
                text-align: center;
                color: rgb(255, 255, 255);
            }

            main h1 {
                margin-bottom: 25px;
                font-size: 35px;
            }

            main p {
                font-size: 20px;
                font-weight: 600;
            }

            main .button-area {
                margin-top: 50px;
            }

            .button-area a {
                padding: 17px 30px;
                background: #da9168;
                border: none;
                border-radius: 30px;
                color: rgb(0, 0, 0);
                font-weight: 600;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
            }

            .button-area a {
                margin-inline: 10px;
            }

            .button-area a:hover {
                color: #767685;
            }


        }
    </style>
</head>

<body>

    <main>
        <div class="title">
            <h1>シンプルな的中管理</h1>
            <p>日々の記録をスムーズに</p>
        </div>
        <div class="button-area">
            <a href="{{ route('register') }}" class="btn-pageChange ">新規登録</a>
            <a href="{{ route('login') }}" class="btn-pageChange ">ログイン</a>
        </div>
    </main>

</body>

</html>