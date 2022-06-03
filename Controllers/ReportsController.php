<?php
require_once(ROOT_PATH .'/Models/Reports.php');
require_once(ROOT_PATH .'/Models/Comments.php');
require_once(ROOT_PATH .'/Models/Validation.php');

    class ReportsController {
          private $request;  //リクエストパラメータ(GET,POST)
          private $Reports;   //Reportsモデル
          private $Comments;   //Reportsモデル
          private $Validation;   //Validationモデル

    public function __construct() {
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        //モデルオブジェクトの生成
        $this->Reports = new Reports();

        //別モデルとの連携
        $dbh = $this->Reports->get_db_handler();
        $this->Comments = new Comments($dbh);
        $this->Validation = new Validation($dbh);
      }



    public function Report() {
      $report_src = 'comment';
      if (! $creature_id = filter_input(INPUT_POST, 'creature_id') || ! is_numeric($creature_id)) {
        echo '指定のパラメータが不正です。このページを表示できません。41<br>';
        echo '<a href="../index.php">トップページへ戻る</a>';
        exit();
      }else {
        $creature_id = $this->request['post']['creature_id'];
      }
      if ($comment_id = filter_input(INPUT_POST, 'comment_id')) {
        if (! is_numeric($comment_id)) {
          echo '指定のパラメータが不正です。このページを表示できません。2<br>';
          echo '<a href="../index.php">トップページへ戻る</a>';
          exit();
        }
      }else {
        //生物情報の通報だった場合
        $report_src = 'creature';
      }

      $valid = $this->Validation->ReportValidation();
        if ($valid == false) {
          //エラーがあった場合戻す
          $_SESSION['error']['report'] = '通報理由が選択されていません。';

          if ($report_src == 'creature') {
            header('Location: ../post_detail.php?creature_id='.$creature_id);
            exit();
          }else {
            //コメントのあったページを取得
            $comments = $this->Comments->select_comments($creature_id);
            $c_count = count($comments);

              for ($i = 1 ; $i <= $c_count ; $i++) {
                echo $comments[$i - 1]['comment_id'];
                echo "<br> ";
                if ($comment_id == $comments[$i - 1]['comment_id']) {
                  $page = ceil($i / 10);
                  break;
                }

              }
             header('Location: ../comments_list.php?creature_id='. $creature_id. '&page='. $page);
             exit();
          }
          return;
        }

      if ($valid == true) {
        //DBへ登録
        $report = $this->Reports->insert_reports();
        if ($report == true) {
          return "通報しました<br>ご協力ありがとうございます";
        }else {
          return "通報に失敗しました<br>もう一度登録し直してください。";
        }
      }

    }




      /**
       * 通報一覧を全件数取得する
       *
       * @return [type] [description]
       */
    public function Reports_list() {
        $page = 1;

        if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
          $page = $this->request['get']['page'];
        } else {
          header('Location: ../reports_list.php?page=1');
        }


        $r_list = $this->Reports->select_reports_list($page);
        $r_count = count( $this->Reports->select_reports());

        $max_page = ceil($r_count / 10);

        $from_record = ($page - 1) * 10 + 1;

        if ($page == $max_page && $r_count % 10 !== 0) {
          $to_record = ($page - 1) * 10 + $r_count % 10;
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
          'reports' => $r_list,
          'page' => $page,
          'max' => $max_page,
          'count' => $r_count,
          'from' => $from_record,
          'to' => $to_record

        ];
        return $params;
      }




}
?>
