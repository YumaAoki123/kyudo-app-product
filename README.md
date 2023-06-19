# 弓道管理 Pro

## 概要
-   弓道の的中データを入力、管理、分析をするためのアプリケーションです。
-   単に的中率を記録するだけでなく、視覚的な情報も取り入れることで、より自分の傾向を分析しやすくなっています。
![image1](https://github.com/YumaAoki123/kyudo-app-product/assets/131954321/d35b7de4-084c-448c-88b8-88f16f071490)
![image2](https://github.com/YumaAoki123/kyudo-app-product/assets/131954321/b76dbdbc-1ff5-44d8-8901-98b9fa066056)
![image3](https://github.com/YumaAoki123/kyudo-app-product/assets/131954321/913f2767-b617-4788-85d6-534df4f2836e)
## 使用技術

-   PHP v8.2.5
-   Laravel v10.10.1
-   Laravel Breeze v1.21
-   Bootstrap v4.5.0
-   jQuery v3.6.0
-   Chart.js v4.3.0
-   Ajax
-   MariaDB v10.5
-   Apache v2.4.6
-   Linux

## 工夫した点

-   Ajax 通信を一部用いてユーザエクスペリエンスの向上。
-   N+1 問題を回避するために、Eager Loading を意識したデータ取得。
-   データの整合性を重視した処理の実施。

## 苦労した点

-   直近 10 個のデータを折れ線グラフでマイページに表示する際、右から左に進むグラフがなかったので、orderBy やグラフそのものを反転させたりして対処した点。
-   store メソッド内で、データ保存後にリダイレクトしようとしても全くリダイレクトがおこらず、Ajax の通信を理解して解決した点。
