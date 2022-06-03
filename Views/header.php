
<header>
  <nav>

    <div id="logo">
      <a href="../index.php?page=1"><img src="../img/logo.png" alt="ロゴ"></a>
    </div><!-- logo -->


    <div class="menu">
      <div class="rect_wrap">
        <a href="../search.php">
          <div class="menu_box" id="menu1">
            <img class="icon" src="../img/icon/search.png" alt="検索">
            <p class="menu_text">検 索</p>
          </div>
        </a>
      </div>

      <?php if (empty($_SESSION['login_user']) || $_SESSION['login_user']['admin'] == 0) :?>
      <div class="rect_wrap">
        <a href="../post.php">
          <div class="menu_box" id="menu2">
            <img class="icon" src="../img/icon/post.png" alt="投稿">
            <p class="menu_text">投 稿</p>
          </div>
        </a>
      </div>

    <?php elseif ($_SESSION['login_user']['admin'] == 1) :?>

      <div class="rect_wrap">
        <a href="../reports_list.php?page=1">
          <div class="menu_box" id="menu2">
            <img class="icon" src="../img/icon/alert.png" alt="通報一覧">
            <p class="menu_text">通報一覧</p>
          </div>
        </a>
      </div>
    <?php endif; ?>

    <?php if (empty($_SESSION['login_user'])) :?>
      <div id="login">
        <a href="../login.php"><img src="../img/login.png" alt="ログイン・新規登録"></a>
      </div><!-- login -->

    <?php else : ?>

      <div class="rect_wrap">
        <a href="../mypage.php?user_id=<?php echo $_SESSION['login_user']['user_id']; ?>">
          <div class="menu_box" id="menu3">
            <img class="icon" src="../img/icon/mypage.png" alt="Myページ">
            <p class="menu_text" class="header_text">Myページ</p>
          </div>
        </a>
      </div>

    <?php endif; ?>







</div><!-- menu -->

</nav>

</header>
