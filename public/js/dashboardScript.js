// 的中率を取得して的の色を更新
const accuracy = {{ $statisticsData['accuracy'] }};
const targetElement = document.getElementById('target');

if (accuracy >= 80) {
    targetElement.classList.add('high-accuracy');
} else if (accuracy >= 50) {
    targetElement.classList.add('medium-accuracy');
} else {
    targetElement.classList.add('low-accuracy');
}

