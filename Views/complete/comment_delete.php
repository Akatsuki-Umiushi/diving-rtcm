<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CommentsController.php');
$users = new UsersController();
$comments = new CommentsController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$result = $users->use_check_token();
$delete = $comments->use_comment_delete();
$creature_id = $_POST['creature_id'];

 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜コメント削除完了</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/header_footer.css">
    <link rel="stylesheet" href="../css/complete.css">
    <link rel="stylesheet" href="../css/common.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/../header.php'); ?>
<div class="wrap">
  <div class="complete">

  <p>コメントの削除が完了しました</p>

  <div class="text-center">
    <a href="<?php echo '../post_detail.php?creature_id='. $creature_id  ; ?>" class="btn btn-primary">投稿を確認する</a>
  </div>

  </div><!-- complete -->
</div><!-- wrap -->
</div> <!-- wrapper -->
<?php include(__DIR__ . '/../footer.php'); ?>

 </body>

</html>
