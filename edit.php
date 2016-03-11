<?php

require_once('config.php');
require_once('functions.php');

// 受け取ったレコードのID
$id = $_GET['id'];

// データベースへの接続
$dbh = connectDb();

// SQLの準備と実行
$sql = "select * from tasks where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

// 結果の取得
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// タスクの編集
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 受け取ったデータ
    $title = $_POST['title'];

    // エラーチェック用の配列
    $errors = array();

    // バリデーション
    if ($title == '') {
        $errors['title'] = 'タスク名を入力してください';
    }

    if ($title == $post['title']) {
        $errors['title'] = 'タスク名が変更されていません';
    }

    // エラーが1つもなければレコードを更新
    if (empty($errors)) {
        $dbh = connectDb();

        $sql = "update tasks set title = :title, updated_at = now() where id = :id";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>TASK EDIT</h2>

<p>
    <form action="" method="post">
        <input type="text" name="title" value="<?php echo h($post['title']); ?>">
        <input type="submit" value="編集">
        <span style="color:red;"><?php echo h($errors['title']); ?></span>
    </form>
</p>
</body>
</html>