<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/DiscoveredController.php');
$users = new UsersController();
$discovered = new DiscoveredController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$user_id = $_SESSION['login_user']['user_id'];
$token = $users->use_set_token();
$params = $discovered->Mitsuketa_list();
$mitsuketa =  $users->h($params['discovered']);
$creature_id = $_GET['creature_id'];
$page = $params['page'];
$max_page = $params['max'];
$count = $params['count'];
$from_record = $params['from'];
$to_record = $params['to'];

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
    <title>ダイビングリアルタイム生物マップ｜みつけた！</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <script src="./js/bootstrap.js"></script>
    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/5d3cb21654.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/discovered_list.css">
  </head>

<body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>
  <div class="wrap">

    <div class="d-flex justify-content-between title">
        <a href="../post_detail.php?creature_id=<?php echo $creature_id ;?>"><i class="fa fa-arrow-left fs-2"></i></a>
        <p class=" fw-bold fs-2 ">みつけた！</p>
        <div class="ps-4"></div>
    </div>

    <div class="list_wrap">

      <?php foreach ($mitsuketa as $m) : ?>
        <?php $user = $users->h($users->use_get_user_by_id($m['user_id'])); ?>
          <div class="mitsuketa_detail" >

            <div class="user_wrap d-flex justify-content-between">
              <div class="user">
                <a class="user_img" href="./mypage.php?user_id=<?php echo $m['user_id']; ?>"><img src="<?php echo $user['image']; ?>" alt=""></a>
               <div class="user_name">
                 <a href="./mypage.php?user_id=<?php echo $m['user_id']; ?>"><?php echo $user['name']; ?></a>
               </div><!-- user_name -->
             </div><!-- user -->

<?php if ($m['user_id'] == $user_id  || $_SESSION['login_user']['admin'] == 1) : ?>
             <div class="discovered_menu">
               <img src="./img/icon/3ten.png">
             </div>

             <!-- 投稿メニュー ダイアログ -->
                   <div class="modal_window d_modal_menu">

                     <div class="d-flex flex-column align-items-center">
                         <form action="./complete/discovered_delete.php" name="delete" method="post" class="w-100">
                           <input type="hidden" name="del_id" value="<?php echo  $m['discovered_id']; ?>">
                           <input type="hidden" name="token" value="<?php echo $token; ?>">
                           <button class="btn btn-dark d-block mx-auto mt-5 w-75" onclick="return confirm('この投稿を削除してもよろしいですか？')" type="submit">削除する</button>
                         </form>

                     <button class="btn btn-secondary my-5 w-75" id="cancel" type="button" name="cancel" >キャンセル</button>
                   </div>
                 </div>
<?php endif; ?>
           </div>

           <div class="mitsuketa_discovery_time">
             <p>発見時刻　</p>
           <p><?php echo date('Y年m月d日 H時i分', strtotime($m['discovered_datetime'])); ?> 頃</p>
         </div><!-- mitsuketa_discovery_time -->

           <div class="post_time">
             <p>投稿時刻 <?php echo $m['created_at']; ?></p>
           </div><!-- post_time -->

          </div><!-- mitsuketa_detail -->


      <?php endforeach; ?>

  </div><!-- list_wrap -->


<!-- ページング -->
  <?php if ($count > 20) : ?>
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

      <?php //最終ページかつ20件以上なら表示 ?>
      <?php if ($page == $max_page && $count > 20)://20 ?>
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

      <?php //1ページ目かつ、20件以上なら表示 ?>
      <?php if ($page == 1 && $count > 20): ?>
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

  <div class="overlay" id="overlay">

  </div><!-- overlay -->

</div><!-- wrap -->
</div><!-- wrapper -->
<?php include(__DIR__ . '../footer.php'); ?>
<script src="./js/main.js"></script>
</body>
</html>
