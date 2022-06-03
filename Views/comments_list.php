<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CommentsController.php');
$users = new UsersController();
$comments = new CommentsController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$user_id = $_SESSION['login_user']['user_id'];
$creature_id = $_GET['creature_id'];
$token = $users->use_set_token();
$params = $comments->Comments_list();
$comment =  $users->h($params['comments']);
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



//エラーメッセージと送信した内容を変数に格納
if (!empty($_SESSION['error'])) {
  $err = $_SESSION['error'];

  if (!empty($_SESSION['post'])) {
   $post = $_SESSION['post'];
  }
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
        <p class=" fw-bold fs-2 ">コメント</p>
        <div class="ps-4"></div>
    </div>

    <?php if (isset($err['report'])) :?>
      <label class="d-block text-danger fw-bold text-center"><?php echo $err['report']; ?></label>
      <br>
    <?php endif; ?>

    <div class="list_wrap">



                   <?php foreach ($comment as $c) : ?>
                     <?php $user = $users->h($users->use_get_user_by_id($c['user_id'])); ?>
                       <div class="comment_detail" >
                         <div class="user_wrap d-flex justify-content-between" id="comment<?php echo $c['comment_id'] ?>">
                           <div class="user">
                             <a class="user_img" href="./mypage.php?user_id=<?php echo $c['user_id']; ?>"><img src="<?php echo $user['image']; ?>" alt=""></a>
                            <div class="user_name">
                              <a href="./mypage.php?user_id=<?php echo $c['user_id']; ?>"><?php echo $user['name']; ?></a>
                            </div><!-- user_name -->
                          </div><!-- user -->


                           <div class="comment_menu">
                             <img src="./img/icon/3ten.png">
                           </div>

                           <!-- 投稿メニュー ダイアログ -->
                                 <div class="modal_window c_modal_menu">

                                   <div class="d-flex flex-column align-items-center">
                       <?php if ($c['user_id'] == $user_id  || $_SESSION['login_user']['admin'] == 1) : ?>
                                       <form action="./complete/comment_delete.php" name="delete" method="post" class="w-100">
                                         <input type="hidden" name="del_id" value="<?php echo  $c['comment_id']; ?>">
                                         <input type="hidden" name="token" value="<?php echo $token; ?>">
                                         <input type="hidden" name="creature_id" value="<?php echo $creature_id; ?>">
                                         <button class="btn btn-dark d-block mx-auto mt-5 w-75" onclick="return confirm('このコメントを削除してもよろしいですか？')" type="submit">削除する</button>
                                       </form>
                       <?php endif; ?>

                       <?php if ($c['user_id'] != $user_id): ?>
                                      <button class="btn btn-dark mt-5 w-75 c_report_button" type="button" name="report">通報する</button>
                       <?php endif; ?>

                                      <button class="btn btn-secondary my-5 w-75 c_cancel1" type="button" name="cancel" >キャンセル</button>
                                  </div>
                               </div>

                       <?php if ($c['user_id'] != $user_id): ?>
                     <!-- コメント通報モーダル -->
                             <div class="modal_window c_report">
                               <div class="d-flex flex-column align-items-center">
                                 <h3>コメントの通報</h3>
                                 <p>通報する理由を選択してください。</p>

                               <form  action="./complete/report.php" method="post" class="d-flex w-75 flex-column">

                                 <div class="form-check mb-4 mt-2">
                                   <input class="form-check-input" type="radio" id="report_radio1_<?php echo $c['comment_id']; ?>" name="reason_for_report" value="スパム/広告">
                                   <label class="form-check-label" for="report_radio1_<?php echo $c['comment_id']; ?>">スパム/広告</label>
                                 </div>

                                 <div class="form-check mb-4">
                                   <input class="form-check-input" type="radio" id="report_radio2_<?php echo $c['comment_id']; ?>" name="reason_for_report" value="性的コンテンツ/出会い目的">
                                   <label class="form-check-label" for="report_radio2_<?php echo $c['comment_id']; ?>">性的コンテンツ/出会い目的</label>
                                 </div>

                                 <div class="form-check mb-4">
                                   <input class="form-check-input" type="radio" id="report_radio3_<?php echo $c['comment_id']; ?>" name="reason_for_report" value="迷惑行為">
                                   <label class="form-check-label" for="report_radio3_<?php echo $c['comment_id']; ?>">迷惑行為</label>
                                 </div>

                                 <div class="form-check mb-4">
                                   <input class="form-check-input" type="radio" id="report_radio4_<?php echo $c['comment_id']; ?>" name="reason_for_report" value="生物に関係のない写真・投稿">
                                   <label class="form-check-label" for="report_radio4_<?php echo $c['comment_id']; ?>">生物に関係のない写真・投稿</label>
                                 </div>

                                 <div class="form-check mb-4">
                                   <input class="form-check-input" type="radio" id="report_radio5_<?php echo $c['comment_id']; ?>" name="reason_for_report" value="その他">
                                   <label class="form-check-label" for="report_radio5_<?php echo $c['comment_id']; ?>">その他</label>
                                 </div>

                                 <button type="submit" class="btn btn-primary mb-4">送信</button>
                                 <button type="button" class="btn btn-secondary mb-4 c_cancel2" data-bs-dismiss="modal">キャンセル</button>
                                 <input type="hidden" name="creature_id" value="<?php echo $creature_id; ?>">
                                 <input type="hidden" name="comment_id" value="<?php echo $c['comment_id']; ?>">
                                 <input type="hidden" name="token" value="<?php echo $token; ?>">
                               </form>
                             </div>
                           </div><!-- modal_window c_report -->

                      <?php endif; ?>

                           </div><!-- user_wrap -->

                           <!-- コメント -->
                          <p class="comment"><?php echo nl2br($c['comment']); ?></p>

                          <div class="post_time">
                            <p>投稿時刻 <?php echo $c['created_at']; ?></p>
                          </div><!-- post_time -->

                       </div><!-- comment_detail -->
                   <?php endforeach; ?>


                  </div><!-- list_wrap -->


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

      <?php //最終ページかつ20件以上なら表示 ?>
      <?php if ($page == $max_page && $count > 20): ?>
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
