<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
require_once(ROOT_PATH .'/Controllers/CommentsController.php');
require_once(ROOT_PATH .'/Controllers/ReportsController.php');
$users = new UsersController();
$comments = new CommentsController();
$reports = new ReportsController();
$a_c = $users->admin_check();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$token = $users->use_set_token();
$params = $reports->Reports_list();
$report =  $users->h($params['reports']);
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
  <title>ダイビングリアルタイム生物マップ｜通報一覧</title>
  <link rel="stylesheet" href="./css/header_footer.css">
  <link rel="stylesheet" href="./css/common.css">
  <link rel="stylesheet" href="./css/list.css">
  <!-- BootstrapのCSS読み込み -->
  <link href="./bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery読み込み -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- BootstrapのJS読み込み -->
  <script src="./bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="wrapper">

    <?php include(__DIR__ . '/header.php'); ?>
      <h1>通報一覧</h1>

      <table class="table table table-hover table-striped table-bordered">
        <tr>
          <th>ID</th>
          <th>通報者ID</th>
          <th>被通報者ID</th>
          <th>被通報者ユーザー名</th>
          <th>通報内容</th>
          <th>通報日時</th>
          <th>詳細</th>
          <th>停止</th>
        </tr>


        <?php  foreach ($report as $r) : ?>
          <tr>
            <td> <?php echo $r['report_id']; ?></td>
            <td> <?php echo $r['report_user_id']; ?></td>
          <?php if ($r['cm_user_id'] != NULL) ://コメントの通報 ?>
            <td> <?php echo $r['cm_user_id']; ?></td>
            <?php $user = $users->h($users->use_get_user_by_id($r['cm_user_id'])); ?>
          <?php else ://生物情報の通報 ?>
            <td> <?php echo $r['c_user_id']; ?></td>
            <?php $user = $users->h($users->use_get_user_by_id($r['c_user_id'])); ?>
          <?php endif; ?>
            <td> <?php echo $user['name']; ?></td>
            <td> <?php echo $r['reason_for_report']; ?></td>
            <td> <?php echo $r['created_at']; ?></td>
          <?php if ($r['comment_id'] != NULL) ://コメントの通報 ?>
            <?php $to_page = $comments->report_comment_check($r['creature_id'], $r['comment_id']);?>
            <td><a class="btn btn-primary" href="../comments_list.php?creature_id=<?php echo $r['creature_id']; ?>&page=<?php echo $to_page; ?>#comment<?php echo $r['comment_id']; ?>">詳細</a></td>
          <?php else ://生物情報の通報 ?>
            <td><a class="btn btn-primary" href="../post_detail.php?creature_id=<?php echo $r['creature_id']; ?>">詳細</a></td>
          <?php endif; ?>
            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stopModal<?php echo $r['report_id']; ?>">停止</button></td>

          </tr>
          <div class="modal fade" id="stopModal<?php echo $r['report_id']; ?>" tabindex="-1" aria-labelledby="stopModalLabel<?php echo $r['report_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="stopModal<?php echo $r['report_id']; ?>Label">アカウント停止期間を選択</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <h6>停止ユーザー</h6>
                  <p class="m-0">ユーザーID　<?php if ($r['cm_user_id'] != NULL){echo $r['cm_user_id'];}else {echo $r['c_user_id'];} ?></p>
                  <p>ユーザー名　<?php echo $user['name']; ?></p>
                  <form class="" action="./complete/user_stop.php" method="post">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" id="stop_radio1<?php echo $r['report_id']; ?>" name="account_stop_period" value="<?php echo date("Y-m-d H:i",strtotime("+3 day")); ?>">
                      <label class="form-check-label" for="stop_radio1<?php echo $r['report_id']; ?>">3日間</label>
                    </div>

                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" id="stop_radio2<?php echo $r['report_id']; ?>" name="account_stop_period" value="<?php echo date("Y-m-d H:i",strtotime("+1 week")); ?>">
                      <label class="form-check-label" for="stop_radio2<?php echo $r['report_id']; ?>">1週間</label>
                    </div>

                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" id="stop_radio3<?php echo $r['report_id']; ?>" name="account_stop_period" value="<?php echo date("Y-m-d H:i",strtotime("+1 month")); ?>">
                      <label class="form-check-label" for="stop_radio3<?php echo $r['report_id']; ?>">1ヶ月</label>
                    </div>

                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" id="stop_radio4<?php echo $r['report_id']; ?>" name="account_stop_period" value="<?php echo date("Y-m-d H:i",strtotime("+3 month")); ?>">
                      <label class="form-check-label" for="stop_radio4<?php echo $r['report_id']; ?>">3ヶ月</label>
                    </div>

                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" id="stop_radio5<?php echo $r['report_id']; ?>" name="account_stop_period" value="<?php echo "9999-12-31 23:59:59"; ?>">
                      <label class="form-check-label" for="stop_radio5<?php echo $r['report_id']; ?>">永久</label>
                    </div>
                    <input type="hidden" name="user_id" value="<?php if ($r['cm_user_id'] != NULL){echo $r['cm_user_id'];}else {echo $r['c_user_id'];} ?>">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="submit" class="btn btn-primary">アカウント停止</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

        <?php endforeach; ?>
      </table>

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



  </div><!-- wrapper -->
  <?php include(__DIR__ . '/footer.php'); ?>

  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="./js/list.js"></script>
</body>
</html>
