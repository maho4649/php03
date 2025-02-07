<?php
session_start();

// セッションがまだ開始されていなければ初期化
if (!isset($_SESSION['question_number'])) {
    $_SESSION['question_number'] = 0;
    $_SESSION['score'] = 0;
}

// 現在の問題を取得
require_once('config/status_codes.php');
$question_number = $_SESSION['question_number'];

if ($question_number >= 10) {
    header('Location: result.php'); // 10問終わったら結果ページへ
    exit();
}

$question = $status_codes[$question_number];

// ランダムに4つの選択肢を取得
$random_indexes = array_rand($status_codes, 4);
$options = [];
foreach ($random_indexes as $index) {
    $options[] = $status_codes[$index];
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Code Quiz</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/sanitize.css">
  <link rel="stylesheet" href="css/common.css">
  
  
</head>
<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/">
        Status Code Quiz
      </a>
    </div>
  </header>

  <main>
    <div class="quiz__content">
      <div class="question">
        <p class="question__text">Q. 以下の内容に当てはまる物を選んでください</p>
        <p class="question__text">
          <?php echo $question['description']; ?>
        </p>
      </div>
      <form class="quiz-form" action="result.php" method="post">
        <input type="hidden" name="answer_code" value="<?php echo $question['code']; ?>">
        <div class="quiz-form__item">
          <?php foreach ($options as $option) : ?>
            <div class="quiz-form__group">
              <input class="quiz-form__radio" id="<?php echo $option['code']; ?>" type="radio" name="option" value="<?php echo $option['code']; ?>">
              <label class="quiz-form__label" for="<?php echo $option['code']; ?>"><?php echo $option['code']; ?></label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="quiz-form__button">
          <button class="quiz-form__button-submit" type="submit">回答</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
