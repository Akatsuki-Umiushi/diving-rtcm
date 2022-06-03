<?php
require_once(ROOT_PATH .'/Controllers/UsersController.php');

session_start();
//エラーメッセージを変数に格納
if (!empty($_SESSION['error'])) {
  $err = $_SESSION['error'];
}

//セッションの初期化
$_SESSION = array();
session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜新規登録</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/login.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>



<div class="wrap">


  <div class="login">
    <form class="login_form" action="./complete/signup_complete.php" method="post">

      <p class="form_title">新規登録</p>

      <div class="login_box">
           <?php if (isset($err['email'])) :?>
             <label style="color : red"><?php echo $err['email']; ?></label>
             <br>
           <?php endif; ?>
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" placeholder="メールアドレス">
      </div><!-- login_box -->

      <div class="login_box">
          <?php if (isset($err['password'])) :?>
            <label style="color : red"><?php echo $err['password']; ?></label>
            <br>
          <?php endif; ?>
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" placeholder="パスワード">
      </div><!-- login_box -->

      <div class="login_box">
        <?php if (isset($err['password_conf'])) :?>
            <label style="color : red"><?php echo $err['password_conf']; ?></label>
            <br>
          <?php endif; ?>
        <label for="password_conf">パスワード（確認）</label>
        <input id="password_conf" type="password" name="password_conf" placeholder="パスワード（確認）">
      </div><!-- login_box -->

      <input class="btn btn-warning mt-3 text-light fw-bold w-100" type="submit" value="新 規 登 録">


    </form>
  </div><!-- login -->



</div><!-- wrap -->
</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
 </body>

</html>
