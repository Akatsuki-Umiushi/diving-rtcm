<?php $row["comment_id"] = 1; ?>
<?php $row["discovered_id"] = 1; ?>
<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CreaturesController.php');
require_once(ROOT_PATH .'/Controllers/GoodsController.php');
require_once(ROOT_PATH .'/Controllers/DiscoveredController.php');
require_once(ROOT_PATH .'/Controllers/CommentsController.php');
$users = new UsersController();
$creatures = new CreaturesController();
$goods = new GoodsController();
$discovered = new DiscoveredController();
$comments = new CommentsController();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$creature = $users->h($creatures->use_get_creature());
$creature_id = $_GET['creature_id'];
$user_id = $_SESSION['login_user']['user_id'];
$good_count = $goods->use_get_good_count($_GET['creature_id']);
$good_check = $goods->use_good_check();//1ならいいね済み。0はいいねしていない。
$post_user = $users->h($users->use_get_user_by_id($creature['user_id']));
$type_name = $creatures->use_get_type($creature['type_id'])['name'];
$color_name = $creatures->get_creature_color_name($creature['creature_id']);
$mitsuketa =  $users->h($discovered->get_mitsuketa_at_3($creature_id));
$mitsuketa_count = $discovered->mitsuketa_count($creature['creature_id']);
$comment =  $users->h($comments->get_comments_at_3($creature_id));
$comments_count = $comments->comments_count($creature['creature_id']);

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
    <title>ダイビングリアルタイム生物マップ｜投稿詳細</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="./bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./css/header_footer.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/post_detail.css">
  </head>
  <body>
<div class="wrapper">
<?php include(__DIR__ . '/header.php'); ?>

  <div class="wrap">

<!-- 生物写真 -->
    <div class="detail_img">
      <img src="<?php echo $creature['image']; ?>" alt="写真"> </div>


    <div class="detail_wrap">
<!-- 投稿時刻 -->
      <div class="post_time">
        <p>投稿時刻 <?php echo $creature['created_at']; ?></p>
      </div><!-- post_time -->

      <?php if (isset($err['report'])) :?>
        <label class="d-block text-danger text-center fw-bold"><?php echo $err['report']; ?></label>
        <br>
      <?php endif; ?>

<!-- いいね、メニュー -->
      <div class="good_and_menu">
        <div class="good" data-bs-creature_id="<?php echo $creature_id; ?>" data-bs-good_index="<?php echo $good_check; ?>">
          <img class="good_img" >
          <span></span>
        </div>

        <div class="post_detail_menu">
          <img src="./img/icon/3ten.png">
        </div>
      </div>

<!-- 投稿ユーザー -->
         <div class="user">
          <a class="user_img" href="./mypage.php?user_id=<?php echo $creature['user_id']; ?>"><img src="<?php echo $post_user['image']; ?>" alt=""></a>
          <div class="user_name">
            <a href="./mypage.php?user_id=<?php echo $creature['user_id']; ?>"><?php echo $post_user['name']; ?></a>
          </div><!-- user_name -->
        </div><!-- user -->


<!-- みつけた！、いいね件数 -->
        <div class="count">

          <a href="./discovered_list.php?creature_id=<?php echo $creature_id; ?>&page=1" class="mitsuketa_count text-dark">
            <p>みつけた！ <span><?php echo $mitsuketa_count ; ?></span>件</p>
          </a><!-- mitsuketa_count -->

          <a href="./good_list.php?creature_id=<?php echo $creature_id; ?>&page=1" class="good_count text-dark">
            <p>いいね <span class="g_count"><?php echo $good_count; ?></span>件</p>
          </a><!-- good_count -->

        </div><!-- count -->


<!-- 生物詳細 -->
           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">名 前</p>
            </div>
             <p><?php echo $creature['name']; ?></p>
           </div><!-- detail_tr -->

           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">種 類</p>
            </div>
             <p><?php echo $type_name; ?></p>
           </div><!-- detail_tr -->

           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">ダイビング<br>スポット</p>
            </div>
             <p><?php echo $creature['spot']; ?></p>
           </div><!-- detail_tr -->

           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">ポイント</p>
            </div>
             <p><?php echo $creature['point']; ?></p>
           </div><!-- detail_tr -->

           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">発見日時</p>
            </div>
             <p><?php echo date('Y年m月d日 H時i分', strtotime($creature['discovery_datetime'])); ?> 頃</p>
           </div><!-- detail_tr -->

           <div class="detail_tr">
            <div class="detail_th">
              <p class="text-center">色</p>
            </div>
             <p>
               <?php if (! empty($color_name)) { echo implode(' 、', $color_name); }  ?>
             </p>
           </div><!-- detail_tr -->


<!-- 詳細コメント -->

        <div class="list_wrap">
          <p>詳細コメント</p>
          <p class="comment"><?php echo nl2br($creature['body']); ?></p>
        </div><!-- list_wrap -->


<!-- みつけた！一覧 -->

    <div class="list_wrap" id="discovered_title">
      <?php if (isset($err['discovery_date'])) :?>
        <label class="text-danger fw-bold" ><?php echo $err['discovery_date']; ?></label>
        <br>
        <br>
      <?php endif; ?>
      <?php if (isset($err['discovery_time'])) :?>
        <label class="text-danger fw-bold"><?php echo $err['discovery_time']; ?></label>
        <br>
        <br>
      <?php endif; ?>
          <div class="list_title" >
            <p>みつけた！一覧</p>
            <?php if ($creature['user_id'] != $user_id): ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#discoveredModal">みつけた！を追加する</button>
            <?php endif; ?>
          </div><!-- list_title -->

<!-- 件数に応じて呼び出す -->
      <?php foreach ($mitsuketa as $m) : ?>
        <?php $user = $users->h($users->use_get_user_by_id($m['user_id'])); ?>
          <div class="mitsuketa_detail">
            <div class="user_wrap d-flex justify-content-between" >
              <div class="user">
                <a class="user_img" href="./mypage.php?user_id=<?php echo $m['user_id']; ?>"><img src="<?php echo $user['image']; ?>" alt=""></a>
               <div class="user_name">
                 <a href="./mypage.php?user_id=<?php echo $m['user_id']; ?>"><?php echo $user['name']; ?></a>
               </div><!-- user_name -->
             </div><!-- user -->

 <?php if ($m['user_id'] == $user_id || $_SESSION['login_user']['admin'] == 1) : ?>
              <div class="discovered_menu">
                <img src="./img/icon/3ten.png">
              </div>

              <!-- 投稿メニュー ダイアログ -->
                    <div class="modal_window d_modal_menu">

                      <div class="d-flex flex-column align-items-center">
                          <form action="./complete/discovered_delete.php" name="delete" method="post" class="w-100">
                            <input type="hidden" name="del_id" value="<?php echo  $m['discovered_id']; ?>">
                            <input type="hidden" name="creature_id" value="<?php echo $creature_id; ?>">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <button class="btn btn-dark d-block mx-auto mt-5 w-75" onclick="return confirm('このみつけた！を削除してもよろしいですか？')" type="submit">削除する</button>
                          </form>

                      <button class="btn btn-secondary my-5 w-75 cancel" type="button" name="cancel" >キャンセル</button>
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

      <?php if ($mitsuketa_count > 3): ?>
          <div class="container text-center">
            <a href="./discovered_list.php?page=1&creature_id=<?php echo $creature_id; ?>" class="btn btn-secondary">もっと見る</a>
          </div>
      <?php endif; ?>
     </div><!-- list_wrap -->




<!-- コメント一覧 -->
     <div class="list_wrap" id="comment_title">

       <?php if (isset($err['comment'])) :?>
         <label class="text-danger fw-bold"><?php echo $err['comment']; ?></label>
         <br>
         <br>
       <?php endif; ?>
       <div class="list_title">
         <p>コメント一覧</p>
             <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#commentModal">コメントを追加する</button>
       </div><!-- list_title -->

       <!-- 件数に応じて呼び出す -->

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
                                   <input type="hidden" name="del_id" value="<?php echo $c['comment_id']; ?>">
                                   <input type="hidden" name="creature_id" value="<?php echo $creature_id; ?>">
                                   <input type="hidden" name="token" value="<?php echo $token; ?>">
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

             <?php if ($comments_count > 3): ?>
                 <div class="container text-center">
                   <a href="./comments_list.php?page=1&creature_id=<?php echo $creature_id; ?>" class="btn btn-secondary">もっと見る</a>
                 </div>
             <?php endif; ?>
            </div><!-- list_wrap -->

    </div><!-- detail_wrap -->

  </div><!-- wrap -->


<!-- みつけた！投稿ダイアログ -->
      <div class="modal fade" id="discoveredModal" tabindex="-1" aria-labelledby="discoveredModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="discoveredModalLabel">みつけた！の追加</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="./complete/discovered_post_complete.php" method="post">
                <label for="discovery_date" class="form-label">発見日時</label>
                <input type="date" class="form-control" id="discovery_date" max="<?php echo date('Y-m-d'); ?>" name="discovery_date" value="<?php echo date('Y-m-d'); ?>">
                <input type="time" class="form-control" name="discovery_time" value="<?php echo date('H:i'); ?>">
                <input type="hidden" name="creature_id" value="<?php echo $creature_id ; ?>">
                <input type="hidden" name="token" value="<?php echo $token ; ?>">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
              <button type="submit" class="btn btn-primary">追加する</button>
              </form>
            </div>
          </div>
        </div>
      </div>


<!-- コメント投稿ダイアログ -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">コメントの追加</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="./complete/comment_post.php" method="post">
                  <label for="comment_post" class="form-label">コメント</label>
                  <textarea class="form-control" name="comment" maxlength="200" rows="8" cols="80"></textarea>
                  <input type="hidden" name="creature_id" value="<?php echo $creature_id ; ?>">
                  <input type="hidden" name="token" value="<?php echo $token ; ?>">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                <button type="submit" class="btn btn-primary">追加する</button>
                </form>
              </div>
            </div>
          </div>
        </div>



<!-- 投稿メニュー ダイアログ -->
      <div class="modal_window modal_menu">

        <div class="d-flex flex-column align-items-center">

          <?php if ($user_id == $creature['user_id']): ?>
            <form action="../post_edit.php" method="post" class="w-100">
              <input type="hidden" name="creature_id" value="<?php echo  $creature['creature_id']; ?>">
              <button class="btn btn-dark d-block mx-auto mt-5 w-75" type="submit" onclick="location.href='post_edit.php'">投稿を編集する</button>
            </form>
          <?php else: ?>
              <button class="btn btn-dark mt-5 w-75" id="report_button" type="button" name="report">通報する</button>
          <?php endif; ?>

          <?php if ($user_id == $creature['user_id'] || $_SESSION['login_user']['admin'] == 1 ): ?>
            <form action="./complete/post_delete.php" name="delete" method="post" class="w-100">
              <input type="hidden" name="del_id" value="<?php echo  $creature['creature_id']; ?>">
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <button class="btn btn-dark d-block mx-auto mt-5 w-75" onclick="return confirm('この投稿を削除してもよろしいですか？')" type="submit">削除する</button>
            </form>
          <?php endif; ?>


        <button class="btn btn-secondary my-5 w-75" id="cancel1" type="button" name="cancel" >キャンセル</button>
      </div>
    </div>

    <div class="overlay" id="overlay">

    </div><!-- overlay -->

    <div class="modal_window report">
      <div class="d-flex flex-column align-items-center">
        <h3>通 報</h3>
        <p>通報する理由を選択してください。</p>

      <form  action="./complete/report.php" method="post" class="d-flex w-75 flex-column">

        <div class="form-check mb-4 mt-2">
          <input class="form-check-input" type="radio" id="report_radio1" name="reason_for_report" value="スパム/広告">
          <label class="form-check-label" for="report_radio1">スパム/広告</label>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" id="report_radio2" name="reason_for_report" value="性的コンテンツ/出会い目的">
          <label class="form-check-label" for="report_radio2">性的コンテンツ/出会い目的</label>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" id="report_radio3" name="reason_for_report" value="迷惑行為">
          <label class="form-check-label" for="report_radio3">迷惑行為</label>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" id="report_radio4" name="reason_for_report" value="生物に関係のない写真・投稿">
          <label class="form-check-label" for="report_radio4">生物に関係のない写真・投稿</label>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" id="report_radio5" name="reason_for_report" value="その他">
          <label class="form-check-label" for="report_radio5">その他</label>
        </div>

        <button type="submit" class="btn btn-primary mb-4">送信</button>
        <button type="button" class="btn btn-secondary mb-4" id="cancel2" data-bs-dismiss="modal">キャンセル</button>


        <input type="hidden" name="creature_id" value="<?php echo $creature_id; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
      </form>
    </div>
  </div>



</div> <!-- wrapper -->
<?php include(__DIR__ . '/footer.php'); ?>
<!-- js -->
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>

<script src="./js/main.js"></script>
<script src="./js/good.js"></script>
 </body>

</html>
