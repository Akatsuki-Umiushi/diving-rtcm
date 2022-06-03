<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$t_c = $users->use_check_token();
$sent_mail = $users->pass_reset_sent_mail();

 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜パスワード再設定メール送信</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/header_footer.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/complete.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/../header.php'); ?>



<div class="wrap">

<div class="complete">
  <p>メールを送信しました<br/>受信したメールのURLからパスワードを再設定してください</p>
</div>

</div><!-- wrap -->

</div> <!-- wrapper -->
<?php include(__DIR__ . '/../footer.php'); ?>
 </body>

</html>
