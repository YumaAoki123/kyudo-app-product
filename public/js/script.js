const target = document.getElementById("target");
let pointCount = 0; // ポイントのカウンタ
let shotCount = 0; // 初期の射撃回数
const shotCountSelect = document.getElementById("shotCountSelect");

const pointsData = []; // ポイントデータを格納する配列

target.addEventListener("click", function(event) {
  if (pointCount >= shotCount || shotCount <= 0) {
    return; 
  }
  const targetRect = target.getBoundingClientRect();
  const x = event.clientX - targetRect.left;
  const y = event.clientY - targetRect.top;
  
  createPoint(x, y);

});


function createPoint(x, y) {
  if (pointCount >= shotCount) {
    return;
  }

  const point = document.createElement("div");
  point.className = "point";
  point.style.left = x + "px";
  point.style.top = y + "px";

  const pointNumber = document.createElement("span"); // ポイント番号を表示する要素
  pointNumber.className = "pointNumber";
  pointNumber.innerText = ++pointCount; // ポイント番号をカウントアップして設定

  point.appendChild(pointNumber); // ポイントにポイント番号を追加

  target.appendChild(point);

    // 一時保存してあるポイントとポイント番号の情報を配列に追加
    const pointData = { x, y, pointNumber: pointCount ,shotCount: shotCount};
    pointsData.push(pointData);

      

   // 一時保存してあるポイントとポイント番号の情報を表示
   console.log("ポイント:", { x, y });
   console.log("ポイント番号:", pointCount);
   console.log("射撃回数:", shotCount);
}



shotCountSelect.addEventListener("change", function() {
  shotCount = parseInt(shotCountSelect.value);
  // 射撃回数が変更された場合にターゲットをリセット
  
});

function resetTarget() {
  pointCount = 0;
  const points = document.getElementsByClassName("point");
  while (points.length > 0) {
    points[0].parentNode.removeChild(points[0]);
  }
   // ポイントデータを初期化
  pointsData.length = 0;
 
}

const clearButton = document.getElementById("clear-button"); // ボタンを取得する

clearButton.addEventListener("click", function() { // ボタンにクリックイベントを追加する
  // const points = document.querySelectorAll(".point"); // .pointクラスの要素を取得する
  // points.forEach(function(point) {
  //   point.remove(); // ポイントを削除する


  // });
  resetTarget();
});


function sendDataToServer() {
  // ポイントデータをJSON形式に変換
  const jsonData = JSON.stringify(pointsData);

  // Ajaxリクエストを作成
  const xhr = new XMLHttpRequest();
  xhr.open("POST", postStoreRoute, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
  

  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        console.log("データが正常に送信されました。");
        // レスポンスを処理する必要がある場合はここで処理する
      } else {
        console.log("データの送信に失敗しました。");
        // エラーハンドリングを行う必要がある場合はここで処理する
      }
    }
  };

  // データを送信
  xhr.send(jsonData);
}


const submitButton = document.getElementById("submitButton");

submitButton.addEventListener("click", function(event) {
  event.preventDefault(); // フォームのデフォルトの送信動作をキャンセル
  
  // ポイントデータをサーバーに送信
  sendDataToServer();

    // 別のページに遷移

    // window.location.href = "/kyudo-app-product/public/result.blade.php";

    // ターゲットと射撃回数をリセット
  resetTarget();
  shotCountSelect.value = "";

});



