<?php
require_once(ROOT_PATH .'/Models/Db.php');


class Comments extends Db {
  public function __construct($dbh = null) {
      parent::__construct($dbh);
  }


  public function insert_comment() {
    $creature_id = $_POST['creature_id'];
    $user_id = $_SESSION['login_user']['user_id'];
    $comment = $_POST['comment'];

        $sql = 'INSERT INTO comments (user_id, creature_id, comment) VALUES (:user_id, :creature_id, :comment)';

    try {
        $this->dbh-> beginTransaction();

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->bindParam(':comment', $comment, PDO::PARAM_STR);
        $sth->execute();

        $result = $sth->rowCount();
        if ($result != 1) {
          throw new \Exception("登録失敗", 1);
        }


        $this->dbh->commit();
        return true;

    } catch (\Exception $e) {
          $this->dbh->rollback();
         echo ('エラー発生：'.$e->getMessage());
          return false;
     }

  }

      /**
       * 3件のコメントを取得
       * @param  int $creature_id
       * @return array
       */
      public function select_comments_at_3($creature_id) {
          $sql = 'SELECT * FROM comments WHERE del_flg = 0 AND creature_id = :creature_id ORDER BY created_at DESC ';
          $sql .= ' LIMIT 3 OFFSET 0 ';
          $sth = $this->dbh->prepare($sql);
          $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
          $sth->execute();

          $result = $sth->fetchAll(PDO::FETCH_ASSOC);
          return $result;
      }


    /**
     * commentsテーブルから指定IDの一部データを取得
     *
     *  @param int $page ページ番号
     *  @return array $result 全データ
     */
    public function select_comments_list($page = 1, $creature_id) {
      $sql = 'SELECT * FROM comments WHERE del_flg = 0 AND creature_id = :creature_id ORDER BY created_at DESC ';
      $sql .= ' LIMIT 10 OFFSET '.(10 * ($page -1) );
      try {
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;

      } catch (\Exception $e) {
        echo $e->getMessage();
      }


    }



      /**
       * commentsテーブルから指定したIDの全データを取得
       * @param  int $creature_id
       * @return array $result
       */
    public function select_comments($creature_id) {
        $sql = 'SELECT * FROM comments WHERE del_flg = 0 AND creature_id = :creature_id ORDER BY created_at DESC ';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }



    public function comment_delete($del_id) {
        $sql = 'UPDATE comments SET del_flg = 1 WHERE comment_id = :del_id ';
        $sth = $this->dbh->prepare($sql);
        try {
          $sth->bindParam(':del_id', $del_id, PDO::PARAM_INT);
          $sth->execute();

          return true;

        } catch (\Exception $e) {
          echo $e->getMessage();
          return false;
        }
      }




}
