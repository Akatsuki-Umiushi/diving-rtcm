<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
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
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/../header.php'); ?>

<div class="wrap">

  <div class="login">
    <form class="login_form" action="./reset_mail_send.php" method="post">

      <p class="form_title">パスワードリセット</p>

      <p>メールアドレスを入力してください</p>

      <div class="login_box">
        <input id="email" type="email" name="email" placeholder="メールアドレス">
      </div><!-- login_box -->


      <button type="submit" class="btn btn-danger w-100 fw-bold">送　信</button>
      <input type="hidden" name="token" value="<?php echo $token; ?>">

    </form>
  </div><!-- login -->


</div><!-- wrap -->

</div> <!-- wrapper -->
<?php include(__DIR__ . '/../footer.php'); ?>
 </body>

</html>
