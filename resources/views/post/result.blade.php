<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>result</title>

    <link rel="stylesheet" href="{{ asset('css/resultStyle.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="target" id="target">
                    <div class="ring ring-0"></div>
                    <div class="ring ring-1"></div>
                    <div class="ring ring-2"></div>
                    <div class="ring ring-3"></div>
                    <div class="ring ring-4"></div>
                    <div class="ring ring-5"></div>
                    <div class="ring ring-6"></div>

                    <?php
                    // // 的中したポイントを赤丸で表示
                    // foreach ($pointsData as $point) {
                    //     $x = $point['pointX'];
                    //     $y = $point['pointY'];
                    //     echo "<div class='point' style='top: {$y}px; left: {$x}px;'></div>";
                    // }
                    ?>

                </div>
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
                    <tr>
                        <td>射撃回数</td>
                        <td><?php //echo $totalCount; 
                            ?></td>
                    </tr>
                    <tr>
                        <td>的中回数</td>
                        <td><?php // echo $hitCount; 
                            ?></td>
                    </tr>
                    <tr>
                        <td>的中率</td>
                        <td><?php // echo round($accuracy, 2) . '%'; 
                            ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <form action="index.html" method="POST">
        <div class="row justify-content-center">
            <div class="col-6">

                <input type="submit" value="トップへ戻る" id="submitButton" name="submitButton">


            </div>
        </div>
    </form>


    </div>
</body>

</html>