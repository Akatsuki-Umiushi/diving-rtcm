<?php
require_once(ROOT_PATH .'/Models/Creatures.php');
require_once(ROOT_PATH .'/Models/Validation.php');
require_once(ROOT_PATH .'/Models/Goods.php');

    class CreaturesController {
          private $request;  //リクエストパラメータ(GET,POST)
          private $Creatures;   //Creaturesモデル
          private $Validation;   //Validationモデル
          private $Goods;   //Validationモデル

    public function __construct() {
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Creatures = new Creatures();

        //別モデルとの連携
        $dbh = $this->Creatures->get_db_handler();
        $this->Validation = new Validation($dbh);
        $this->Goods = new Goods($dbh);
      }



      /**
      * 生物情報の削除されていない、全件数を新着順で取得する
       *
       * @return [type] [description]
       */
    public function index() {
        $page = 1;
        if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
          $page = $this->request['get']['page'];
        } else {
          header('Location: ../index.php?page=1');
        }


        $creatures = $this->Creatures->findAll($page);
        $creatures_count = $this->Creatures->countAll();

        $max_page = ceil($creatures_count / 10);

        $from_record = ($page - 1) * 10 + 1;

        if ($page == $max_page && $creatures_count % 10 !== 0) {
          $to_record = ($page - 1) * 10 + $creatures_count % 10;
        }else {
          $to_record = $page * 10;
        }

        if ($page > $max_page && $max_page != 0) {
          $uri = '..'.$_SERVER['REQUEST_URI'];
          $now_page = 'page='. $this->request['get']['page'];
          $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

          header('Location:'. $over_uri );
        }

        $params = [
          'creatures' => $creatures,
          'page' => $page,
          'max' => $max_page,
          'count' => $creatures_count,
          'from' => $from_record,
          'to' => $to_record

        ];
        return $params;
      }



      /**
       * 指定したIDの生物情報を全件数、新着順で取得する
       *
       * @return
       */
    public function My_creatures() {
        $page = 1;

        if (empty($this->request['get']['user_id']) && ! is_numeric($this->request['get']['user_id'])) {
          echo 'お探しのページは見つかりません。<br>';
          echo '<a href="../index.php?page=1">トップページへ戻る</a>';
          exit();
        }

        if (isset($this->request['get']['page']) && is_numeric($this->request['get']['page'])) {
          $page = $this->request['get']['page'];
        } else {
          header('Location: ../mypage.php?page=1&user_id='.$this->request['get']['user_id']);
        }


        $my_creatures = $this->Creatures->my_creaturesAll($page, $this->request['get']['user_id']);
        $my_creatures_count = $this->Creatures->my_creatures_count($this->request['get']['user_id']);

        $max_page = ceil($my_creatures_count / 10);

        $from_record = ($page - 1) * 10 + 1;

        if ($page == $max_page && $my_creatures_count % 10 !== 0) {
          $to_record = ($page - 1) * 10 + $my_creatures_count % 10;
        }else {
          $to_record = $page * 10;
        }

        if ($page > $max_page && $max_page != 0) {
          $uri = '..'.$_SERVER['REQUEST_URI'];
          $now_page = 'page='. $this->request['get']['page'];
          $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

          header('Location:'. $over_uri );
        }

        $params = [
          'creatures' => $my_creatures,
          'page' => $page,
          'max' => $max_page,
          'count' => $my_creatures_count,
          'from' => $from_record,
          'to' => $to_record

        ];
        return $params;
      }


      /**
       * 自分がいいねした生物情報を全件数、新着順で取得する
       *
       * @return
       */
    public function My_good_creatures() {
        $page = 1;
        $creatures_id = array();
        if (empty($_SESSION['login_user']['user_id'])) {
          $_SESSION['err']['msg'] = 'ログインがされていない、またはセッションが切れています。<br>恐れ入りますが、ログインしてください。 ';
          header('Location: ../login.php');
          exit();
        }

        if (isset($this->request['get']['page']) && is_numeric($this->request['get']['page'])) {
          $page = $this->request['get']['page'];
        } else {
          header('Location: ../my_good_list.php?page=1');
        }


        $good_creatures = $this->Goods->get_good_at_user_id($_SESSION['login_user']['user_id']);
        foreach ($good_creatures as $row) {
          $creatures_id[] = $row['creature_id'];
        };

        if ($creatures_id == NULL) {
          $params = [
            'creatures' => $good_creatures,
            'page' => $page,
            'max' => 1,
            'count' => 0,
            'from' => 0,
            'to' => 0

          ];
          return $params;

        }else {

          $my_creatures = $this->Creatures->my_good_creaturesAll($page, $creatures_id);
          $my_creatures_count = $this->Creatures->my_good_creatures_count($creatures_id);

          $max_page = ceil($my_creatures_count / 10);

          $from_record = ($page - 1) * 10 + 1;

          if ($page == $max_page && $my_creatures_count % 10 !== 0) {
            $to_record = ($page - 1) * 10 + $my_creatures_count % 10;
          }else {
            $to_record = $page * 10;
          }

          if ($page > $max_page && $max_page != 0) {
            $uri = '..'.$_SERVER['REQUEST_URI'];
            $now_page = 'page='. $this->request['get']['page'];
            $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

            header('Location:'. $over_uri );
          }

          $params = [
            'creatures' => $my_creatures,
            'page' => $page,
            'max' => $max_page,
            'count' => $my_creatures_count,
            'from' => $from_record,
            'to' => $to_record

          ];
          return $params;
        }
      }


      /**
       * 生物情報の削除されていない、検索条件にあった値を新着順で取得する
       *
       * @return array
       */
    public function Search() {
      if (! isset($this->request['get']['page'])
       || ! isset($this->request['get']['name'])
       || ! isset($this->request['get']['type_id'])
       || ! isset($this->request['get']['spot'])
       || ! isset($this->request['get']['point'])
       || ! isset($this->request['get']['color_id'])
       || ! isset($this->request['get']['start_date'])
       || ! isset($this->request['get']['end_date'])) {
         echo '指定のパラメータが不正です。このページを表示できません。<br>';
         echo '<a href="../index.php">トップページへ戻る</a>';
         exit();
      }

        if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
          $page = $this->request['get']['page'];
        } else {
          $page = 1;
        }


        $creatures = $this->Creatures->search($page);
        $creatures_count = $this->Creatures->count_search();
        $max_page = ceil($creatures_count / 10);

        $from_record = ($page - 1) * 10 + 1;

        if ($page == $max_page && $creatures_count % 10 !== 0) {
          $to_record = ($page - 1) * 10 + $creatures_count % 10;
        }else {
          $to_record = $page * 10;
        }

        if ($page > $max_page && $max_page != 0) {
          $uri = '..'.$_SERVER['REQUEST_URI'];
          $now_page = 'page='. $this->request['get']['page'];
          $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

          header('Location:'. $over_uri );
        }

        $params = [
          'creatures' => $creatures,
          'page' => $page,
          'max' => $max_page,
          'count' => $creatures_count,
          'from' => $from_record,
          'to' => $to_record,
        ];
        return $params;
      }




      /**
       * 投稿を処理
       *
       * @return int  creature_id
       */
      public function Post() {
        if (empty($this->request['post'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }
        //バリデーション
        $error = $this->Validation->PostValidation($this->request['post']);

          if (count($error) > 0) {
              //エラーがあった場合戻す
              $_SESSION['error'] = $error;
              $_SESSION['post'] = $_POST;
              if (! empty($_FILES['image']['tmp_name'])) {
                $_SESSION['post']['image'] = 'imageあり';
              }
              header('Location: ../post.php');
              return;
          }else {
              //画像の移動、パス取得
              $path = $this->Validation->get_file_path('creatures');
              if ($path == false ) {
                $_SESSION['post'] = $_POST;
                header('Location: ../post.php');
                return;
              }
              //DBへの登録
              $result = $this->Creatures->insert_creature($this->request['post'], $path);

              if ($result == false || $result == '0') {
                $_SESSION['post'] = $_POST;
                header('Location: ../post.php');
                return;
              }else {
                return $result;
              }

          }

      }



      /**
       * 投稿を編集
       *
       * @return int  creature_id
       */
      public function PostEdit() {
        if (empty($this->request['post'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }
        //バリデーション
        $error = $this->Validation->PostValidation($this->request['post']);

          if (count($error) > 0) {
              //エラーがあった場合戻す
              $_SESSION['error'] = $error;
              $_SESSION['post'] = $this->request['post'];
              $_SESSION['creature_id'] = $this->request['post']['creature_id'];
              if (! empty($_FILES['image']['tmp_name'])) {
                $_SESSION['post']['image'] = 'imageあり';
              }
              header('Location: ../post_edit.php');
              return;
          }else {
            //画像の移動、パス取得
              if (! empty($_FILES['image']['tmp_name'])) {
                $path = $this->Validation->get_file_path('creatures');
                if ($path == false ) {
                  $_SESSION['post'] = $_POST;
                  header('Location: ../post.php');
                  return;
                }
              }else {
                $path = 0;
              }

              //DBへの登録
              $result = $this->Creatures->creature_update($this->request['post'], $path);


              if ($result == false || $result == '0') {
                $_SESSION['post'] = $_POST;
                $_SESSION['creature_id'] = $this->request['post']['creature_id'];
                $_SESSION['creature_id'] = $this->request['post']['creature_id'];
                header('Location: ../post.php');
                return;
              }else {
                return $this->request['post']['creature_id'];
              }

          }

      }

      /**
       * クエリパラメータから生物情報を取得
       * @return bool(false)||array
       */
      public function use_get_creature() {
        if (empty($this->request['get']['creature_id'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();

        }else {
          $creature_id = $this->request['get']['creature_id'];
        }

        $result = $this->Creatures->get_creature($creature_id);
        if ($result == false) {
          echo 'お探しのページは存在しません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }
        return $result;

      }

      /**
       * クエリパラメータから生物情報を取得
       * @return bool(false)||array
       */
      public function get_edit_creature() {
        if (empty($this->request['post']['creature_id']) && empty($_SESSION['creature_id'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();

        }else {
          if (! empty($this->request['post']['creature_id']) ) {
            $creature_id = $this->request['post']['creature_id'];
          }else
            $creature_id = $_SESSION['creature_id'];
        }

        $result = $this->Creatures->get_creature($creature_id);
        if ($result == false) {
          echo 'お探しのページは存在しません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }
        return $result;

      }

      /**
       * 生物情報を削除
       * @return [type] [description]
       */
      public function use_creature_delete() {
        if (! empty($this->request['post']['del_id'])) {
          $del_creature = $this->Creatures->creature_delete($this->request['post']['del_id']);

          if ($del_creature == true) {
            return true;
          }else {
            return "削除に失敗しました。";
          }
        }
        return "削除済み、または存在しません。";
      }


      /**
       * 生物の種類情報を取得
       * @param  int $type_id
       * @return array||bool
       */
      public function use_get_type($type_id) {
        $result = $this->Creatures->get_type($type_id);
        return $result;

      }


      /**
       * 生物のカラーIDを取得
       * @param  int $creature_id
       * @return array||bool(false)
       */
      public function use_get_color_id($creature_id) {
        $result = $this->Creatures->get_color_id($creature_id);
        return $result;

      }

      /**
       * カラーの名前を取得
       * @param  array $color_id
       * @return array||bool(false)
       */
      public function use_get_color_name($color_id) {
        $result = $this->Creatures->get_color_name($color_id);
        return $result;

      }

      /**
       * 生物の発見時刻を日付と時刻に分割
       * @param  string $datetime
       * @return array||bool(false)
       */
      public function use_get_date_time($datetime) {
        $result = $this->Creatures->get_date_time($datetime);
        return $result;

      }


      /**
       * 生物の色名を取得
       * @param  int $creature_id
       * @return str||array||bool(false)
       */
      public function get_creature_color_name($creature_id) {
        $color_id = $this->Creatures->get_color_id($creature_id);
        $result = $this->Creatures->get_color_name($color_id);
        return $result;
      }



}
 ?>
