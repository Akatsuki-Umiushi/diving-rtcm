<?php
session_start();
require_once(ROOT_PATH .'/Controllers/UsersController.php');
$users = new UsersController();
$a_c = $users->admin_check();
$login_c = $users->use_loginCheck();//ログインしているかチェック
$token = $users->use_set_token();
$params = $users->UsersList();
$users_list =  $users->h($params['users']);
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
    <title>ダイビングリアルタイム生物マップ｜ユーザー一覧</title>
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


    <h1>ユーザー一覧</h1>

      <table class="table table table-hover table-striped table-bordered">
        <tr>
          <th>ユーザーID</th>
          <th>ユーザー名</th>
          <th>メールアドレス</th>
          <th>登録日時</th>
          <th>アカウント停止解除日</th>
        </tr>


      <?php  foreach ($users_list as $user) : ?>
           <tr>
              <td><?php echo $user['user_id']; ?></td>
              <td><a href="./mypage.php?user_id=<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></a></td>
              <td><?php echo $user['email']; ?></td>
              <td><?php echo $user['created_at']; ?></td>
              <td><?php echo $user['user_stop']; ?></td>
          </tr>
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
</body>
</html>
