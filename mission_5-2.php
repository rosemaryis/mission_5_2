<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-2</title>
</head>
<body>


    <?php

        $dsn = 'mysql:dbname=*******;host=localhost';
        $user = '*******';
        $pass = '******';
        $pdo = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"//テーブル作成//テーブルの内容
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
        . "name char(32),"//名前
        . "comment TEXT,"//コメント
        . "date TEXT,"//日付
        . "password TEXT"//パスワード
        .");";
        $stmt = $pdo->query($sql);

        //編集フォームの送信の有無で処理を分岐
        if (!empty($_POST["edit"]) &&  !empty($_POST["editpass"])) {

            $edit = $_POST['edit'];
            $editpassword = $_POST['editpass'];

            //データ表示(特定のidのデータだけを抽出)
            $id = $edit ; // idがこの値のデータだけを抽出したい、とする
            $password = $editpassword ;

             //投稿番号と編集番号が一致したらその投稿の「名前」と「コメント」を取得
            $sql = 'SELECT * FROM tbtest WHERE id=:id && password=:password';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->bindParam(':password', $password, PDO::PARAM_INT);
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                $editnumber = $row['id'];
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $editpass = $row['password'];
                //既存の投稿フォームに、上記で取得した「名前」と「コメント」の内容が既に入っている状態で表示させる
                    //formのvalue属性で対応
            }
      
            
        }
 

            //名前とコメントに記入がなければ送信されない→格納されない
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
            //editNoがないときは新規投稿モード、ある場合は編集投稿モード
            if (empty($_POST['editNO'])) {
                
                //データを入力（データレコードの挿入）新規投稿モード
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $name = $_POST["name"]; //送信された名前をPHPで受け取り、変数に入れる
                $comment = $_POST["comment"]; //送信されたコメントをPHPで受け取り、変数に入れる
                $date= date("Y/m/d H:i:s"); //送信したときの時間をPHPで受け取り、変数に入れる
                $password = $_POST['pass'];
                $sql -> execute();
                
            
            } else {
                //データを入力（データレコードの挿入）編集投稿モード
                //データの編集
                $editNO = $_POST['editNO'];
                $id = $editNO; //変更する投稿番号
	            $name = $_POST["name"];//変更したい名前
                $comment = $_POST["comment"]; //変更したいコメント
                $date= date("Y/m/d H:i:s"); //送信したときの時間をPHPで受け取り、変数に入れる
                $password = $_POST['pass'];
	            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';//WHERE句で「 name='赤坂一郎' 」とした場合、name が 赤坂一郎 のデータレコードが複数あれば、その全てが更新されます。
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                }
        }


       //削除モード
        if(!empty($_POST["delnumber"]) && !empty($_POST["delpass"])){ 
            $delete= $_POST["delnumber"];
            $delpassword = $_POST['delpass'];

            //データの削除
            $id = $delete;
            $password = $delpassword;
	        $sql = 'delete from tbtest where id=:id && password=:password';
	        $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $password, PDO::PARAM_INT);
            $stmt->execute();

        }

    ?>

    <form action="" method="post"><!--送信-->
    <!--HTMLパートで送信formを作成。「名前」「コメント」の入力と「送信」ボタンが1つあるフォームを作成-->
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"> <!--名前欄-->
    <br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"> <!--コメント欄-->
    <input type="text" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
    <br>
    <input type="text" name="pass" placeholder="password" value="<?php if(isset($editpass)) {echo $editpass;} ?>"> <!--パスワードボタン-->
    <br>
    <input type="submit" name="btn1"> <!--送信ボタン--> 
    <input type="reset" value="リセット"> <!--リセットボタン-->
    <br>
    <hr>
    <!--HTMLパートで編集依頼formを作成。「編集番号」の入力と「編集」ボタンが1つあるフォームを作成-->
    <input type="text" name="edit" placeholder="編集番号"> <!--名前欄-->
    <br>
    <input type="text" name="editpass" placeholder="password"> <!--パスワードボタン-->
    <br>
    <input type="submit" name="btn3" value="編集"> <!--編集ボタン-->
    <br>
    <hr>
    <!--HTMLパートで削除依頼formを作成。「削除番号」の入力と「削除」ボタンが1つあるフォームを作成-->
    <input type="text" name="delnumber" placeholder="削除番号"> <!--名前欄-->
    <br>
    <input type="text" name="delpass" placeholder="password"> <!--パスワードボタン-->
    <br>
    <input type="submit" name="btn2" value="削除"> <!--削除ボタン-->
    <br>
    <hr>
    </form>


    <?php

    //データの表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }

    ?>


</body>

</html>