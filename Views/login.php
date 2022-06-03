<?php
session_start();

//エラーメッセージを変数に格納
if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
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
    <title>ダイビングリアルタイム生物マップ｜ログイン</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
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
    <form class="login_form" action="./complete/login_complete.php" method="post">

      <p class="form_title">ログイン</p>

      <div class="login_box">
        <?php if (isset($error['msg'])) :?>
            <label style="color : red"><?php echo $error['msg']; ?></label>
            <br>
        <?php endif; ?>

        <?php if (isset($error['email'])) :?>
            <label style="color : red"><?php echo $error['email']; ?></label>
            <br>
        <?php endif; ?>
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" placeholder="メールアドレス">
      </div><!-- login_box -->

      <div class="login_box">
        <?php if (isset($error['password'])) :?>
            <label style="color : red"><?php echo $error['password']; ?></label>
            <br>
        <?php endif; ?>
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" placeholder="パスワード">
      </div><!-- login_box -->

      <input class="btn btn-primary mt-3 w-100 fw-bold" type="submit" name="login" value="ログイン">

      <a href="./pass_reset/request.php">パスワードを忘れた方はこちら</a>


    </form>
  </div><!-- login -->

<div class="text-center pt-3 pb-5">
  <button type="button" class="btn btn-warning text-light fw-bold mw-300px" onclick="location.href='signup.php'">新しいアカウントを作成する</button>
</div>



</div><!-- wrap -->
</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>

 </body>

</html>
