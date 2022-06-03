<?php
require_once(ROOT_PATH .'/Models/Discovered.php');
require_once(ROOT_PATH .'/Models/Validation.php');

    class DiscoveredController {
          private $request;  //リクエストパラメータ(GET,POST)
          private $Discovered;   //Discoveredモデル
          private $Validation;   //Validationモデル

    public function __construct() {
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Discovered = new Discovered();

        //別モデルとの連携
        $dbh = $this->Discovered->get_db_handler();
        $this->Validation = new Validation($dbh);
      }



      public function MitsuketaPost() {
        if (empty($this->request['post'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }

        $err = $this->Validation->MitsuketeValidation();
          if (count($err) > 0) {
            //エラーがあった場合戻す
            $_SESSION['error'] = $err;
            header('Location: ../post_detail.php?creature_id='.$this->request['post']['creature_id'].'#discovered_title');
            return;
          }

        if (count($err) === 0) {
          //ユーザー登録
          $discovered_post = $this->Discovered->insert_discovered();
          if ($discovered_post == true) {
            return "みつけた！の投稿が完了しました";
          }else {
            return "みつけた！の登録に失敗しました<br>もう一度登録し直してください。";
          }

        }

      }


      /**
       * みつけた！の詳細を取得する
       *
       * @return int $mitsuketa
       */
      public function get_mitsuketa_at_3($creature_id) {
          $mitsuketa = $this->Discovered->select_discovered_at_3($creature_id);
          return $mitsuketa;
      }


      /**
       * 指定IDのみつけた！情報全件数を新着順で取得する
       *
       * @return [type] [description]
       */
    public function Mitsuketa_list() {
        $page = 1;
        if (empty($this->request['get']['creature_id'])) {
          echo 'お探しのページは見つかりません。<br>';
          echo '<a href="../index.php?page=1">トップページへ戻る</a>';
          exit();
        }

        if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
          $page = $this->request['get']['page'];
        } else {
          header('Location: ../discovered_list.php?creature_id='. $this->request['get']['creature_id']. '&page=1');
        }


        $d_list = $this->Discovered->select_discovered_list($page, $this->request['get']['creature_id']);
        $d_count = count( $this->Discovered->select_discovered($this->request['get']['creature_id']));

        $max_page = ceil($d_count / 20);

        $from_record = ($page - 1) * 20 + 1;

        if ($page == $max_page && $d_count % 20 !== 0) {
          $to_record = ($page - 1) * 20 + $d_count % 20;
        }else {
          $to_record = $page * 20;
        }
        //20

        if ($page > $max_page && $max_page != 0) {
          $uri = '..'.$_SERVER['REQUEST_URI'];
          $now_page = 'page='. $this->request['get']['page'];
          $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

          header('Location:'. $over_uri );
        }

        $params = [
          'discovered' => $d_list,
          'page' => $page,
          'max' => $max_page,
          'count' => $d_count,
          'from' => $from_record,
          'to' => $to_record

        ];
        return $params;
      }



      /**
       * みつけた！の数を取得する
       *
       * @return int $discovered_count
       */
      public function mitsuketa_count($creature_id) {
          $discovered_count = count($this->Discovered->select_discovered($creature_id));
          return $discovered_count;
      }



      public function use_discovered_delete() {
          $del_id = $this->request['post']['del_id'];
          $delete = $this->Discovered->discovered_delete($del_id);

          return $delete;
      }



}
?>
