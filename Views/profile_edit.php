<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CreaturesController.php');
$users = new UsersController();
$creatures = new CreaturesController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$user_id =  $_SESSION['login_user']['user_id'];

$user = $users->h($users->use_get_user_by_id($user_id));

//エラーメッセージと送信した内容を変数に格納
if (!empty($_SESSION['error'])) {
  $err = $_SESSION['error'];
  $post = $_SESSION['post'];
}

//ログイン情報を変数に格納
$login = $_SESSION['login_user'];

//セッションの初期化
$_SESSION = array();
session_destroy();

//セッションの開始、トークン作成
$token = $users->use_set_token();

//ログイン情報の復元
$_SESSION['login_user'] = $login;


 ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜プロフィール編集</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/common_form.css">
    <link rel="stylesheet" href="./css/mypage.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>
  <div class="wrap">

    <div class="user_img">
      <img src="<?php echo $user['image']; ?>" alt="プロフィール画像">
    </div><!-- user_img -->

  <form class="profile_edit_form" action="./complete/profile_edit_complete.php" method="post" enctype="multipart/form-data">

    <div class="form_wrap">
      <div class="mt-3">
        <?php if (isset($err['image'])) :?>
            <label style="color : red"><?php echo $err['image']; ?></label>
            <br>
        <?php endif; ?>

        <?php if (isset($post['image'])) :?>
            <label style="color : red"><?php echo "恐れ入りますが、画像を再選択ください。"; ?></label>
            <br>
        <?php endif; ?>
        <label for="image" class="form-label m-0">プロフィール画像 (※変更がない場合はそのまま送信してください)</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/png, image/jpeg" >
      </div>

      <div class="mt-3">
        <?php if (isset($err['name'])) :?>
            <label style="color : red"><?php echo $err['name']; ?></label>
            <br>
        <?php endif; ?>
        <label for="name" class="form-label m-0">ユーザー名</label>
        <input type="text" name="name" class="form-control" id="name" value="<?php if (isset($post['name'])) {echo $post['name']; }else { echo $user['name']; } ?>">
      </div>

      <div class="mt-3">
        <?php if (isset($err['self_introduction'])) :?>
            <label style="color : red"><?php echo $err['self_introduction']; ?></label>
            <br>
        <?php endif; ?>
        <label for="self_introduction" class="form-label m-0">自己紹介（200文字以内）</label>
        <textarea class="form-control" rows="8" maxlength="200" name="self_introduction" id="self_introduction"><?php if (isset($post['self_introduction'])){echo $post['self_introduction']; }else { echo  $user['self_introduction']; } ?></textarea>
      </div>


      <div class="hr mt-5 mb-5"></div>

      <div class="mt-3">
        <?php if (isset($err['email'])) :?>
            <label style="color : red"><?php echo $err['email']; ?></label>
            <br>
        <?php endif; ?>
        <label for="email" class="form-label m-0">メールアドレス</label>
        <input type="email" name="email" class="form-control" id="email" value="<?php if (isset($post['email'])) {echo $post['email']; }else { echo $user['email']; } ?>">
      </div>

      <div class="mt-3">
        <?php if (isset($err['password'])) :?>
            <label style="color : red"><?php echo $err['password']; ?></label>
            <br>
        <?php endif; ?>
        <label for="password" class="form-label m-0">パスワード</label>
        <input type="password" name="password" class="form-control" id="password">
      </div>

      <div class="mt-3">
        <?php if (isset($err['password_conf'])) :?>
            <label style="color : red"><?php echo $err['password_conf']; ?></label>
            <br>
        <?php endif; ?>
        <label for="password" class="form-label m-0">パスワード確認</label>
        <input type="password" name="password" class="form-control" id="password">
      </div>

      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
      <input type="hidden" name="token" value="<?php echo $token; ?>">


      <button class="btn btn-primary mt-5 w-100" type="submit" >変更する</button>



    </div><!-- form_wrap -->
  </form>

  </div><!-- wrap -->

</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
</body>
</html>
