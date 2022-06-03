<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$user = $users->use_get_pass_reset_user();
$email = $user['email'];
$reset_token = $user['token'];

//エラーメッセージを変数に格納
if (!empty($_SESSION['error'])) {
  $error = $_SESSION['error'];
}
//セッションの初期化
$_SESSION = array();
session_destroy();
//セッションの開始、トークン作成
$token = $users->use_set_token();

 ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜パスワードの再設定</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/header_footer.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/common.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/../header.php'); ?>



<div class="wrap">


  <div class="login">
    <form class="login_form" action="./pass_reset_complete.php" method="post">

      <p class="form_title">パスワードの変更</p>

      <?php if (isset($error['msg'])) :?>
          <label style="color : red"><?php echo $error['msg']; ?></label>
          <br>
      <?php endif; ?>


            <div class="login_box">
              <?php if (isset($error['password'])) :?>
                  <label style="color : red"><?php echo $error['password']; ?></label>
                  <br>
              <?php endif; ?>
              <label for="password">新しいパスワード</label>
              <input id="password" type="password" name="password" placeholder="パスワード">
            </div><!-- login_box -->
            <div class="login_box">
              <?php if (isset($error['password_conf'])) :?>
                  <label style="color : red"><?php echo $error['password_conf']; ?></label>
                  <br>
              <?php endif; ?>
              <label for="password_conf">新しいパスワード（確認）</label>
              <input id="password_conf" type="password" name="password_conf" placeholder="パスワード（確認）">
            </div><!-- login_box -->



      <input class="btn btn-danger w-100 fw-bold" type="submit" name="login" value="送　信">
      <input type="hidden" name="token" value="<?php echo $token; ?>">
      <input type="hidden" name="email" value="<?php echo $email; ?>">
      <input type="hidden" name="reset_token" value="<?php echo $reset_token; ?>">

    </form>
  </div><!-- login -->


</div><!-- wrap -->

</div> <!-- wrapper -->
<?php include(__DIR__ . '/../footer.php'); ?>
 </body>

</html>
