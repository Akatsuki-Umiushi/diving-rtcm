<?php
require_once(ROOT_PATH .'/Models/Db.php');



class Reports extends Db {
  public function __construct($dbh = null) {
      parent::__construct($dbh);
  }


  public function insert_reports() {
    $creature_id = $_POST['creature_id'];
    $comment_id = filter_input(INPUT_POST, 'comment_id');
    $user_id = $_SESSION['login_user']['user_id'];
    $reason_for_report = $_POST['reason_for_report'];

        $sql = 'INSERT INTO reports( user_id, creature_id, comment_id, reason_for_report) VALUES (:user_id, :creature_id, :comment_id, :reason_for_report)';

    try {
        $this->dbh-> beginTransaction();

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $sth->bindParam(':reason_for_report', $reason_for_report, PDO::PARAM_STR);
        $sth->execute();

        $this->dbh->commit();
        return true;

    } catch (\Exception $e) {
          $this->dbh->rollback();
         echo ('エラー発生：'.$e->getMessage());
          return false;
     }
  }


    /**
     * reportsテーブルから全データを取得
     *
     *  @param int $page ページ番号
     *  @return array $result 全データ
     */
    public function select_reports_list($page = 1) {
      $sql = 'SELECT report_id, r.user_id AS report_user_id , cm.user_id AS cm_user_id, c.user_id AS c_user_id, r.creature_id, r.comment_id, reason_for_report, r.created_at FROM reports r LEFT JOIN creatures c ON r.creature_id = c.creature_id LEFT JOIN comments cm ON r.comment_id = cm.comment_id ';
      $sql .= ' LIMIT 10 OFFSET '.(10 * ($page -1) );
      $sth = $this->dbh->prepare($sql);
      $sth->execute();

      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      return $result;

    }



      /**
       * reportsテーブルから指定したIDの全データを取得
       *
       * @return array $result
       */
    public function select_reports() {
        $sql = 'SELECT * FROM reports ';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }




}
