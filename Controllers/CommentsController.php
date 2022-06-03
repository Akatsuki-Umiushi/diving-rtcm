<?php
require_once(ROOT_PATH .'/Models/Comments.php');
require_once(ROOT_PATH .'/Models/Validation.php');

    class CommentsController {
          private $request;  //リクエストパラメータ(GET,POST)
          private $Comments;   //Commentモデル
          private $Validation;   //Commentモデル

    public function __construct() {
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Comments = new Comments();

        //別モデルとの連携
        $dbh = $this->Comments->get_db_handler();
        $this->Validation = new Validation($dbh);
      }


    public function CommentPost() {
        if (empty($this->request['post'])) {
          echo '指定のパラメータが不正です。このページを表示できません。<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }

        $err = $this->Validation->CommentValidation();
          if (count($err) > 0) {
            //エラーがあった場合戻す
            $_SESSION['error'] = $err;
            $_SESSION['post'] =  $_POST;
            header('Location: ../post_detail.php?creature_id='.$this->request['post']['creature_id'].'#comment_title');
            return;
          }

        if (count($err) === 0) {
          //ユーザー登録
          $comment_post = $this->Comments->insert_comment();
          if ($comment_post == true) {
            return "コメントの投稿が完了しました";
          }else {
            return "コメントの登録に失敗しました<br>もう一度登録し直してください。";
          }

        }

    }


    /**
     * 指定IDのコメント情報全件数を新着順で取得する
     *
     * @return array
     */
  public function Comments_list() {
      $page = 1;
      if (empty($this->request['get']['creature_id'])) {
        echo 'お探しのページは見つかりません。<br>';
        echo '<a href="../index.php?page=1">トップページへ戻る</a>';
        exit();
      }

      if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
        $page = $this->request['get']['page'];
      } else {
        header('Location: ../comments_list.php?creature_id='. $this->request['get']['creature_id']. '&page=1');
      }


      $c_list = $this->Comments->select_comments_list($page, $this->request['get']['creature_id']);
      $c_count = count( $this->Comments->select_comments($this->request['get']['creature_id']));

      $max_page = ceil($c_count / 10);

      $from_record = ($page - 1) * 10 + 1;

      if ($page == $max_page && $c_count % 10 !== 0) {
        $to_record = ($page - 1) * 10 + $c_count % 10;
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
        'comments' => $c_list,
        'page' => $page,
        'max' => $max_page,
        'count' => $c_count,
        'from' => $from_record,
        'to' => $to_record

      ];
      return $params;
    }


    /**
     * コメントの詳細を3件取得する
     *
     * @return int $comment
     */
    public function get_comments_at_3($creature_id) {
        $comment = $this->Comments->select_comments_at_3($creature_id);
        return $comment;
    }

    /**
     * コメントの数を取得する
     *
     * @return int $comments_count
     */
    public function comments_count($creature_id) {
        $comments_count = count($this->Comments->select_comments($creature_id));
        return $comments_count;
    }


    public function use_comment_delete() {
        $del_id = $this->request['post']['del_id'];
        $delete = $this->Comments->comment_delete($del_id);

        return $delete;
    }


    public function report_comment_check($creature_id, $comment_id){
      $comments = $this->Comments->select_comments($creature_id);
      $c_count = count($comments);

        for ($i = 1 ; $i <= $c_count ; $i++) {
          if ($comment_id == $comments[$i - 1]['comment_id']) {
            $page = ceil($i / 10);
            return $page;
          }
        }
    }




}
?>
