<?php
    // データベース接続
    $dsn = "mysql:dbname=php_tools;host=localhost;charset=utf8mb4";
    $username = "sora";
    $password = "monster";
    $options = [];
    $pdo = new PDO($dsn, $username, $password, $options);

    // POST処理
    // @$_POSTの値は毎回クリアしないと更新ボタンを押したときに前のクエリ内容が実行される

    // 新規投稿
    if (null !== @$_POST["create"]){
        if (@$_POST["title"] != "" OR @$_POST["contents" != ""]){
            $stmt = $pdo->prepare("INSERT INTO memo(title, contents) VALUE (:title, :contents)");
            $stmt->bindvalue(":title", @$_POST["title"]);
            $stmt->bindvalue(":contents", @$_POST["contents"]);
            $stmt->execute();
        }
    }

    // 上書き処理
    if (null !== @$_POST["update"]){
        $stmt = $pdo->prepare("UPDATE memo SET title=:title, contents=:contents WHERE ID=:id");
        $stmt->bindvalue(":title", @$_POST["title"]);
        $stmt->bindvalue(":contents", @$_POST["contents"]);
        $stmt->bindvalue(":id", @$_POST["id"]);
        $stmt->execute();
    }

    // 削除処理
    if(null !== @$_POST["delete"]){
        $stmt = $pdo->prepare("DELETE FROM memo WHERE ID=:id");
        $stmt->bindvalue(":id", @$_POST["id"]);
        $stmt->execute();
    }
?>


<html>
    <head>
    <title>MEMO</title>
    </head>

    <body>
    新規作成<br>
    <form action="memo.php" method="post">
        Title<br>
        <input type="text" name="title" size="20"></input><br>
        Contents<br>
        <textarea name="contents" style="width:300px; height:100px"></textarea><br>
        <input type="submit" name="create" value="追加">
    </form>
    メモ一覧
    <?php
        // DB内のデータを取得して一覧表示
        $stmt = $pdo->query("SELECT * FROM memo");
        foreach($stmt as $row):
    ?>
        <form action="memo.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row[0]?>"></input>
            TItle<br>
            <input type="text" name="title" size="20" value="<?php echo $row[1]?>"></input><br>
            <textarea name="contents" style="width:300px; height:100px;"><?php echo $row[2] ?></textarea><br>
            <input type="submit" name="update" value="変更">
            <input type="submit" name="delete" value="削除">
        </form>
    <?php
        endforeach;
    ?>
    </body>
</html>