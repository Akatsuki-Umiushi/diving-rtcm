<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$login_c = $users->use_loginCheck();//ログインしているかチェック

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜検索</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/common_form.css">
    <!-- <script src="main.js"></script> -->
  </head>

  <body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>

<div class="wrap">

  <h2>条件を指定して検索</h2>

  <div class="form_wrap">



    <form class="search_form" action="search_result.php" method="get" enctype="multipart/form-data">

      <div class="mt-3">
        <label for="name" class="form-label m-0">生物の名前</label>
        <input type="text" name="name" class="form-control" id="name">
      </div>

       <div class="mt-3">
         <label class="form-label m-0">種類 (複数選択可)</label>
         <input type="checkbox" name="type_id[]" value="" hidden checked>
         <div class="container p-0 m-0 d-flex justify-content-start">

          <div class="container p-0 m-0 d-flex flex-column">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="1" id="type1">
              <label for="type1" class="form-check-label">
                魚類
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="3" id="type3">
              <label for="type3" class="form-check-label">
                イルカ
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="5" id="type5">
              <label for="type5" class="form-check-label">
                エビ
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="7" id="type7">
              <label for="type7" class="form-check-label">
                カメ
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="9" id="type9">
              <label for="type9" class="form-check-label">
                タコ
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="11" id="type11">
              <label for="type11" class="form-check-label">
                ダンゴウオ
              </label>
            </div>

            <div class="form-check  form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="13" id="type13">
              <label for="type13" class="form-check-label">
                その他
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex flex-column">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="2" id="type2">
              <label for="type2" class="form-check-label">
                イカ
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="4" id="type4">
              <label for="type4" class="form-check-label">
                ウミウシ
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="6" id="type6">
              <label for="type6" class="form-check-label">
                カニ
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="8" id="type8">
              <label for="type8" class="form-check-label">
                サメ
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="10" id="type10">
              <label for="type10" class="form-check-label">
                タツノオトシゴ
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="type_id[]" class="form-check-input" value="12" id="type12">
              <label for="type12" class="form-check-label">
                マンタ
              </label>
            </div>
          </div>



        </div>
      </div>


      <div class="mt-3">

        <label class="form-label m-0">色 (複数選択可)</label>
        <input type="checkbox" name="color_id[]" value="" hidden checked>
        <div class="container p-0 m-0 ">

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="1" id="color1">
              <label for="color1" class="form-check-label">
                赤色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="2" id="color2">
              <label for="color2" class="form-check-label">
                青色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="3" id="color3">
              <label for="color3" class="form-check-label">
                黄色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="4" id="color4">
              <label for="color4" class="form-check-label">
                緑色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="5" id="color5">
              <label for="color5" class="form-check-label">
                黒色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="6" id="color6">
              <label for="color6" class="form-check-label">
                白色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="7" id="color7">
              <label for="color7" class="form-check-label">
                水色
              </label>
            </div>
            <div class="form-check form-check-inline m-0">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="8" id="color8">
              <label for="color8" class="form-check-label">
                黄緑色
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="9" id="color9">
              <label for="color9" class="form-check-label">
                紫色
              </label>
            </div>
          </div>

          <div class="container p-0 m-0 d-flex justify-content-between">
            <div class="form-check  form-check-inline m-0">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="10" id="color10">
              <label for="color10" class="form-check-label">
                オレンジ色
              </label>
            </div>
            <div class="form-check  form-check-inline">
              <input type="checkbox" name="color_id[]" class="form-check-input" value="11" id="color11">
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
        <label for="spot" class="form-label m-0">ダイビングスポット</label>
        <input type="text" name="spot" class="form-control" id="spot">
      </div>

      <div class="mt-3">
        <label for="point" class="form-label m-0">ポイント</label>
        <input type="text" name="point" class="form-control" id="point">
      </div>

      <div class="mt-3">
        <label for="start_date" class="form-label m-0">期間</label>
        <div class="d-flex justify-content-between">
          <div class="w-45">
            <input type="date" name="start_date" class="form-control" id="start_date">
          </div>
          <p class="pt-2">～</p>
          <div class="w-45">
            <input type="date" name="end_date" class="form-control" id="end_date">
          </div>
        </div>
      </div>




      <input type="hidden" name="page" value="1">

       <button class="btn btn-primary w-100 mt-5" type="submit" >検　索</button>

    </div><!-- form_wrap -->
</div><!-- wrap -->
</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
 </body>

</html>
