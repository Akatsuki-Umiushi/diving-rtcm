<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$user_id =  $_SESSION['login_user']['user_id'];

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
    <title>ダイビングリアルタイム生物マップ｜投稿</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/common_form.css">
  </head>

  <body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>


<div class="wrap">
    <h1 class="mb-2 fw-bold text-center">生物情報の投稿</h1>

  <div class="form_wrap">

    <form class="post_form" action="./complete/post_complete.php" method="post" enctype="multipart/form-data">

      <h4 class="mt-4 fw-bold"><span class="text-danger">※</span>は入力必須です</h4>

      <?php if (isset($err['msg'])) :?>
        <label style="color : red"><?php echo $err['msg']; ?></label>
        <br>
      <?php endif; ?>

      <div class="mt-3">
        <?php if (isset($err['image'])) :?>
            <label style="color : red"><?php echo $err['image']; ?></label>
            <br>
        <?php endif; ?>

        <?php if (isset($post['image'])) :?>
            <label style="color : red"><?php echo "恐れ入りますが、画像を再選択ください。"; ?></label>
            <br>
        <?php endif; ?>
        <label for="image" class="form-label m-0">写真</label>
        <input type="file" name="image"  class="form-control" id="image" accept="image/png, image/jpeg">
      </div>

      <div class="mt-3">
        <?php if (isset($err['name'])) :?>
            <label style="color : red"><?php echo $err['name']; ?></label>
            <br>
        <?php endif; ?>
        <label for="name" class="form-label m-0">生物の名前<span class="text-danger">※</span></label>
        <input type="text" name="name" class="form-control" id="name" value="<?php if (isset($post['name'])){echo $post['name']; } ?>">
      </div>

      <div class="mt-3">
        <?php if (isset($err['type_id'])) :?>
            <label style="color : red"><?php echo $err['type_id']; ?></label>
            <br>
        <?php endif; ?>
        <label for="type_id" class="form-label m-0">種類<span class="text-danger">※</span></label>
        <select name="type_id" class="form-select" id="type_id" >
          <option value="" selected hidden>選択してください</option>
          <option value="1" <?php if(isset($post['type_id']) && $post['type_id'] == 1 ){echo 'selected'; } ?>>魚類</option>
          <option value="2" <?php if(isset($post['type_id']) && $post['type_id'] == 2 ){echo 'selected'; } ?>>イカ</option>
          <option value="3" <?php if(isset($post['type_id']) && $post['type_id'] == 3 ){echo 'selected'; } ?>>イルカ</option>
          <option value="4" <?php if(isset($post['type_id']) && $post['type_id'] == 4 ){echo 'selected'; } ?>>ウミウシ</option>
          <option value="5" <?php if(isset($post['type_id']) && $post['type_id'] == 5 ){echo 'selected'; } ?>>エビ</option>
          <option value="6" <?php if(isset($post['type_id']) && $post['type_id'] == 6 ){echo 'selected'; } ?>>カニ</option>
          <option value="7" <?php if(isset($post['type_id']) && $post['type_id'] == 7 ){echo 'selected'; } ?>>カメ</option>
          <option value="8" <?php if(isset($post['type_id']) && $post['type_id'] == 8 ){echo 'selected'; } ?>>サメ</option>
          <option value="9" <?php if(isset($post['type_id']) && $post['type_id'] == 9 ){echo 'selected'; } ?>>タコ</option>
          <option value="10" <?php if(isset($post['type_id']) && $post['type_id'] == 10 ){echo 'selected'; } ?>>タツノオトシゴ</option>
          <option value="11" <?php if(isset($post['type_id']) && $post['type_id'] == 11 ){echo 'selected'; } ?>>ダンゴウオ</option>
          <option value="12" <?php if(isset($post['type_id']) && $post['type_id'] == 12 ){echo 'selected'; } ?>>マンタ</option>
          <option value="13" <?php if(isset($post['type_id']) && $post['type_id'] == 13 ){echo 'selected'; } ?>>その他</option>
        </select>
      </div>

      <div class="mt-3">
        <?php if (isset($err['spot'])) :?>
            <label style="color : red"><?php echo $err['spot']; ?></label>
            <br>
        <?php endif; ?>
        <label for="spot" class="form-label m-0">ダイビングスポット<span class="text-danger">※</span></label>
        <input type="text" name="spot" class="form-control" id="spot" value="<?php if (isset($post['spot'])){echo $post['spot']; } ?>">
      </div>


      <div class="mt-3">
        <?php if (isset($err['point'])) :?>
            <label style="color : red"><?php echo $err['point']; ?></label>
            <br>
        <?php endif; ?>
        <label for="point" class="form-label m-0">ポイント</label>
        <input type="text" name="point" class="form-control" id="point" value="<?php if (isset($post['point'])){echo $post['point']; } ?>">
      </div>


      <div class="mt-3">
          <?php if (isset($err['discovery_date'])) :?>
              <label style="color : red"><?php echo $err['discovery_date']; ?></label>
              <br>
          <?php endif; ?>
          <?php if (isset($err['discovery_time'])) :?>
              <label style="color : red"><?php echo $err['discovery_time']; ?></label>
              <br>
          <?php endif; ?>
        <label for="discovery_date">発見日時<span class="text-danger">※</span></label>
        <div class="d-flex justify-content-between">
          <div class="w-47-5">
            <input type="date" name="discovery_date" id="discovery_date" class="form-control" max="<?php echo date('Y-m-d'); ?>" value="<?php if (isset($post['discovery_date'])){echo $post['discovery_date']; }else { echo date('Y-m-d'); } ?>">
          </div>
          <div class="w-47-5">
            <input type="time" name="discovery_time" id="discovery_time" class="form-control" value="<?php if (isset($post['discovery_time'])){echo $post['discovery_time']; }else { echo date('H:i'); } ?>">
          </div>
        </div>
      </div>

      <div class="mt-3">
        <?php if (isset($err['color_id'])) :?>
            <label style="color : red"><?php echo $err['color_id']; ?></label>
            <br>
        <?php endif; ?>

        <label class="form-label m-0">色 (複数選択可)</label>
        <input type="hidden" name="color_id[]" value="0">
        <div class="container p-0 m-0 ">

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="1" id="color1" <?php if(isset($post['color_id']) && in_array(1, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color1" class="form-check-label">
                赤色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="2" id="color2" <?php if(isset($post['color_id']) && in_array(2, $post['color_id']) == true){echo 'checked'; } ?> <?php if(isset($post['color_id']) && in_array(1, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color2" class="form-check-label">
                青色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="3" id="color3" <?php if(isset($post['color_id']) && in_array(3, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color3" class="form-check-label">
                黄色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="4" id="color4" <?php if(isset($post['color_id']) && in_array(4, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color4" class="form-check-label">
                緑色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="5" id="color5" <?php if(isset($post['color_id']) && in_array(5, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color5" class="form-check-label">
                黒色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="6" id="color6" <?php if(isset($post['color_id']) && in_array(6, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color6" class="form-check-label">
                白色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="7" id="color7" <?php if(isset($post['color_id']) && in_array(7, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color7" class="form-check-label">
                水色
              </label>
            </div>
            <div class="form-check form-check-inline m-0">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="8" id="color8" <?php if(isset($post['color_id']) && in_array(8, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color8" class="form-check-label">
                黄緑色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="9" id="color9" <?php if(isset($post['color_id']) && in_array(9, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color9" class="form-check-label">
                紫色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check  form-check-inline m-0">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="10" id="color10" <?php if(isset($post['color_id']) && in_array(10, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color10" class="form-check-label">
                オレンジ色
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="11" id="color11" <?php if(isset($post['color_id']) && in_array(11, $post['color_id']) == true){echo 'checked'; } ?>>
              <label for="color11" class="form-check-label">
                ピンク色
              </label>
            </div>
            <div class="form-check  form-check-inline">
            </div>
          </div>

        </div>
      </div>

      <div class="mt-3">
        <?php if (isset($err['body'])) :?>
            <label style="color : red"><?php echo $err['body']; ?></label>
            <br>
        <?php endif; ?>
        <label for="body" class="form-label m-0">詳細コメント<br/>(発見した場所の詳細など)</label>
        <textarea id="body" class="form-control" name="body" rows="8" maxlength="1000" ><?php if(isset($post['body'])) {echo $post['body']; } ?></textarea>
      </div>

      <input type="hidden" name="token" value="<?php echo $token; ?>">
      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">


      <button type="submit" class="btn btn-danger mt-5 w-100" name="submit">投　稿</button>

    </form>
  </div><!-- form_wrap -->

</div><!-- wrap -->

</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
  </body>

</html>
