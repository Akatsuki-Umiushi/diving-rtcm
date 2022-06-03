<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CreaturesController.php');
require_once(ROOT_PATH .'/Controllers/GoodsController.php');
require_once(ROOT_PATH .'/Controllers/DiscoveredController.php');
$users = new UsersController();
$creatures = new CreaturesController();
$goods = new GoodsController();
$discovered = new DiscoveredController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$params = $creatures->Search();
$creatures_detail = $users->h($params['creatures']);
$page = $params['page'];
$max_page = $params['max'];
$count = $params['count'];
$from_record = $params['from'];
$to_record = $params['to'];
$color_name = $creatures->use_get_color_name($_GET['color_id']);
$get = $users->h($_GET);
sort($_GET['type_id']);
foreach ($_GET['type_id'] as $key => $value) {
  $type_name[] = $creatures->use_get_type($value)['name'];
}


//他のページへのURL
$uri = '..'.$_SERVER['REQUEST_URI'];
$now_page = 'page='. $_GET['page'];

$next_page =  str_replace($now_page, 'page='.($_GET['page'] + 1), $uri);
$next2_page =  str_replace($now_page, 'page='.($_GET['page'] + 2), $uri);
$preview_page =  str_replace($now_page, 'page='.($_GET['page'] - 1), $uri);
$preview2_page =  str_replace($now_page, 'page='.($_GET['page'] - 2), $uri);




?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ダイビングリアルタイム生物マップ｜検索結果</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/search_result.css">
  </head>
<body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>
<div class="wrap">

<!-- 検索条件 -->
<p id="search_condition_p">検索条件</p>

    <div class="condition_items_box">

      <div class="condition_items">
        <p>名前：</p>
        <span class="red"><?php echo $get['name']; ?></span>
      </div><!-- condition_items -->

      <div class="condition_items">
        <p>種類：</p>
        <span class="red">
          <?php foreach ($type_name as $key => $value) {
            echo $value;
            echo " ";
          } ?>
          <?php //echo $get_type; ?>
        </span>
      </div><!-- condition_items -->

      <div class="condition_items">
        <p>色　：</p>
        <span class="red">
          <?php foreach ($color_name as $key => $value) {
            echo $value;
            echo " ";
          } ?>
          <?php //echo $get_color; ?>
        </span>
      </div><!-- condition_items -->

      <div class="condition_items">
        <p>ダイビングスポット：</p>
        <span class="red"><?php echo $get['spot']; ?></span>
      </div><!-- condition_items -->

      <div class="condition_items">
        <p>ポイント：</p>
        <span class="red"><?php echo $get['point']; ?></span>
      </div><!-- condition_items -->

      <div class="condition_items">
        <p>発見時刻：<span class="red"><?php echo $get['start_date']; ?></span>　～　<span class="red"><?php echo $get['end_date']; ?></span></p>
      </div><!-- condition_items -->

    </div><!-- condition_items_box -->



<!-- 検索ボタン -->
<div class="">
  <button class="btn btn-primary d-block mx-auto my-4" type="button" onclick="location.href='search.php'">条 件 を 変 え て 検 索</button>
</div>



<!-- 生物一覧 -->
<div id="card_list_wrap">

<?php foreach ($creatures_detail as $creature) : ?>


    <a href="post_detail.php?creature_id=<?php echo $creature['creature_id']; ?> " class="card_list text-dark">

      <div class="card_list_top">
          <div class="creature_icon">
            <img src="<?php echo $creature['t_image']; ?>" alt="生物アイコン">
          </div><!-- creature_icon -->


          <div class="creature_photo">
            <img src="<?php echo $creature['c_image']; ?>" alt="生物写真">
          </div><!-- creature_photo -->

          <div class="creature_name">
            <p class="bg-light_blue label-bg-margin">名前</p>
            <p><?php echo $creature['name']; ?></p>
          </div><!-- creature_name -->

      </div><!-- card_list_top -->

      <div class="card_list_middle">
          <div class="creature_spot">
            <p class="bg-light_blue label-bg-margin">ダイビングスポット</p>
            <p><?php echo $creature['spot']; ?></p>
          </div><!-- creature_spot -->

          <div class="creature_point">
            <p class="bg-light_blue label-bg-margin">ポイント</p>
            <p><?php echo $creature['point']; ?></p>
          </div><!-- creature_point -->
      </div><!-- card_list_middle -->

      <div class="card_list_bottom">
        <div class="creature_discovery_time">
          <p class="bg-light_blue label-bg-margin">発見時刻</p>
          <p><?php echo date('Y年m月d日 H時i分', strtotime($creature['discovery_datetime'])); ?>頃</p>
        </div><!-- creature_discovery_time -->
      </div><!-- card_list_bottom -->

      <div class="card_list_middle">
          <div class="creature_spot">
            <p class="bg-light_blue label-bg-margin">みつけた！</p>
            <p><?php echo $discovered->mitsuketa_count($creature['creature_id']); ?>件</p>
          </div><!-- creature_spot -->

          <div class="creature_point">
            <p class="bg-light_blue label-bg-margin">いいね</p>
            <p><?php echo $goods->use_get_good_count($creature['creature_id']); ?>件</p>
          </div><!-- creature_point -->
      </div><!-- card_list_middle -->

    </a><!-- card_list -->

  <?php endforeach; ?>

  </div><!-- card_list_wrap -->


  <?php if ($count > 10) : ?>
      <label class="d-block mt-4 text-center"><?php echo $count; ?>件中<?php echo $from_record; ?> - <?php echo $to_record; ?>件目を表示</label>
  <?php elseif ($count == 0) : ?>
      <label class="d-block mt-4 text-center"><?php echo '0件中'; ?><?php echo '0'; ?> - <?php echo '0'; ?>件目を表示</label>
  <?php else: ?>
      <label class="d-block mt-4 text-center"><?php echo $count; ?>件中<?php echo '1'; ?> - <?php echo $count; ?>件目を表示</label>
  <?php endif; ?>

  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-2">

      <?php if ($page >= 2): ?>
            <li class="page-item">
              <a class="page-link" href="<?php echo $preview_page; ?>" aria-label="Previous">&laquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
              <a class="page-link">&laquo;</a>
            </li>
      <?php endif; ?>

      <?php //最終ページなら表示 ?>
      <?php if ($page == $max_page && $count >20)://10 ?>
        <li class="page-item"><a class="page-link" href="<?php echo $preview2_page; ?>"><?php echo ($page - 2); ?></a></li>
      <?php endif; ?>

      <?php //最初のページでなければ表示 ?>
      <?php if (($page - 1) > 0): ?>
        <li class="page-item"><a class="page-link" href="<?php echo $preview_page; ?>"><?php echo ($page - 1); ?></a></li>
      <?php endif; ?>

      <!-- アクティブ -->
      <li class="page-item active"><a class="page-link" href="#"><?php echo $page; ?></a></li>

      <?php //最終ページでなければ表示 ?>
      <?php if ($page < $max_page  ): ?>
        <li class="page-item"><a class="page-link" href="<?php echo $next_page; ?>"><?php echo ($page + 1); ?></a></li>
      <?php endif; ?>

      <?php //1ページ目かつ、10件以上なら表示 ?>
      <?php if ($page == 1 && $count >20)://10 ?>
        <li class="page-item"><a class="page-link" href="<?php echo $next2_page; ?>"><?php echo ($page + 2); ?></a></li>
      <?php endif; ?>


      <?php if ($page < $max_page): ?>
            <li class="page-item">
              <a class="page-link" href="<?php echo $next_page; ?>" aria-label="Next">&raquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
              <a class="page-link">&raquo;</a>
            </li>
      <?php endif; ?>
    </ul>
  </nav>


</div><!-- wrap -->
</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
</body>

</html>
