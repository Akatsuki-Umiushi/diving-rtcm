<?php
require_once(ROOT_PATH .'/Models/Goods.php');

    class GoodsController {
          private $request;  //リクエストパラメータ(GET,POST)
          private $Goods;   //Goodモデル

    public function __construct() {
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Goods = new Goods();

        //別モデルとの連携
        // $dbh = $this->Goods->get_db_handler();
        // $this->Goods = new Goods($dbh);
      }


    /**
     * いいね
     * @return
     */
    public function use_good_toggle() {
      if (! empty($this->request['post']['creatureId'])) {
        $good_toggle = $this->Goods->good_toggle($this->request['post']['creatureId']);

        return $good_toggle;

      }else {
        return;
      }
    }


    /**
     * いいねの数を取得する
     *
     * @return int $good_count
     */
    public function use_get_good_count($creature_id) {
        $good_count = count($this->Goods->get_good($creature_id));

        return $good_count;
    }


    /**
     * いいねしているかどうかをを取得する
     *
     * @return int $good_check 1||0
     * 1はいいね済み。0はいいねしていない
     */
    public function use_good_check() {
      if (! empty($this->request['get']['creature_id']) && ! empty($_SESSION['login_user']['user_id'])) {
        $good_check = $this->Goods->good_check($this->request['get']['creature_id'], $_SESSION['login_user']['user_id']);

        return $good_check;

      }else {
        return;
      }
    }


    /**
     * 指定IDのいいね情報全件数を新着順で取得する
     *
     * @return array
     */
  public function Goods_list() {
      $page = 1;
      if (empty($this->request['get']['creature_id'])) {
        echo 'お探しのページは見つかりません。<br>';
        echo '<a href="../index.php?page=1">トップページへ戻る</a>';
        exit();
      }

      if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
        $page = $this->request['get']['page'];
      } else {
        header('Location: ../good_list.php?creature_id='. $this->request['get']['creature_id']. '&page=1');
      }


      $g_list = $this->Goods->get_good_list($page, $this->request['get']['creature_id']);
      $g_count = count( $this->Goods->get_good($this->request['get']['creature_id']));

      $max_page = ceil($g_count / 20);

      $from_record = ($page - 1) * 20 + 1;

      if ($page == $max_page && $g_count % 20 !== 0) {
        $to_record = ($page - 1) * 20 + $g_count % 20;
      }else {
        $to_record = $page * 20;
      }
    

      if ($page > $max_page && $max_page != 0) {
        $uri = '..'.$_SERVER['REQUEST_URI'];
        $now_page = 'page='. $this->request['get']['page'];
        $over_uri = str_replace($now_page, 'page='.$max_page, $uri);

        header('Location:'. $over_uri );
      }

      $params = [
        'goods' => $g_list,
        'page' => $page,
        'max' => $max_page,
        'count' => $g_count,
        'from' => $from_record,
        'to' => $to_record

      ];
      return $params;
    }


public function use($user_id)
{
  $t = $this->Goods->get_good_at_user_id($user_id);
  return $t;
}


}
?>
