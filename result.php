<?php
session_start();

require_once('config/status_codes.php');

// ユーザーが選んだ答え
$answer_code = htmlspecialchars($_POST['answer_code'], ENT_QUOTES);
$option = htmlspecialchars($_POST['option'], ENT_QUOTES);

// 回答が選ばれていなければ、index.php にリダイレクト
if (!$option) {
    header('Location: index.php');
    exit();
}

// 正解判定
foreach ($status_codes as $status_code) {
    if ($status_code['code'] === $answer_code) {
        $code = $status_code['code'];
        $description = $status_code['description'];
    }
}

$result = ($option === $code);

// スコアの更新
if ($result) {
    $_SESSION['score']++;
}

// 次の問題に進む
$_SESSION['question_number']++;

// 10問目が終わった場合、最終スコアを表示してセッションをリセット
if ($_SESSION['question_number'] >= 10) {
    $final_score = $_SESSION['score'];
    session_unset(); // セッションのリセット
    session_destroy(); // セッションの破棄
    echo "<p>クイズ終了！ あなたのスコアは $final_score/10 です。</p>";
    echo "<p><a href='index.php'>再挑戦する</a></p>"; // 再挑戦リンクを追加
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Code Quiz</title>
  <link rel="stylesheet" href="css/sanitize.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/result.css">
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
    <div class="result__content">
      <div class="result">
        <?php if ($result): ?>
        <h2 class="result__text--correct">正解</h2>
        <?php else: ?>
        <h2 class="result__text--incorrect">不正解</h2>
        <?php endif; ?>
      </div>
      <div class="answer-table">
        <table class="answer-table__inner">
          <tr class="answer-table__row">
            <th class="answer-table__header">ステータスコード</th>
            <td class="answer-table__text">
              <?php echo $code ?>
            </td>
          </tr>
          <tr class="answer-table__row">
            <th class="answer-table__header">説明</th>
            <td class="answer-table__text">
              <?php echo $description ?>
            </td>
          </tr>
        </table>
      </div>
      <div class="next-question">
        <a href="index.php" class="next-question__link">次の問題へ</a>
      </div>
    </div>
  </main>
</body>
</html>
