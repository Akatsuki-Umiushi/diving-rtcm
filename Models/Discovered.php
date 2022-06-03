<?php
require_once(ROOT_PATH .'/Models/Db.php');


class Discovered extends Db {
  public function __construct($dbh = null) {
      parent::__construct($dbh);
  }

  public function insert_discovered() {
        $creature_id = $_POST['creature_id'];
        $user_id = $_SESSION['login_user']['user_id'];
        $discovered_datetime = $_POST['discovery_date']. ' '. $_POST['discovery_time'];

          // discoveredテーブルから投稿IDとユーザーIDが一致したレコードを取得するSQL文
          $sql = 'SELECT * FROM discovered WHERE del_flg = 0 AND creature_id = :creature_id AND user_id = :user_id';

        try {
          $this->dbh->beginTransaction();

          $sth = $this->dbh->prepare($sql);
          $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
          $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
          $sth->execute();
          $count = $sth->rowCount();

          // レコードが1件でもある場合
          if(! empty($count)){
              // レコードを更新する
              $sql = 'UPDATE discovered SET discovered_datetime = :discovered_datetime WHERE del_flg = 0 AND creature_id = :creature_id AND user_id = :user_id';
              $sth = $this->dbh->prepare($sql);
              $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
              $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
              $sth->bindParam(':discovered_datetime', $discovered_datetime, PDO::PARAM_STR);
              $sth->execute();
              $countud = $sth->rowCount();
              if ($countud != 1) {
                throw new \Exception("updatesippai", 1);

              }

              $this->dbh->commit();
              return true;

          }else{
              //レコードを挿入する
              $sql = 'INSERT INTO discovered(user_id, creature_id, discovered_datetime) VALUES (:user_id, :creature_id, :discovered_datetime)';
              $sth = $this->dbh->prepare($sql);
              $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
              $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
              $sth->bindParam(':discovered_datetime', $discovered_datetime, PDO::PARAM_STR);
              $sth->execute();

              $countis = $sth->rowCount();
              if ($countis != 1) {
                throw new \Exception("insertsippai", 1);

              }


              $this->dbh->commit();
              return true;
          }

      } catch (\Exception $e) {
            $this->dbh->rollback();
            return false;
       }
    }


    /**
     * 3件のみつけた！を取得
     * @param  int $creature_id
     * @return array
     */
    public function select_discovered_at_3($creature_id) {
        $sql = 'SELECT * FROM discovered WHERE del_flg = 0 AND creature_id = :creature_id ORDER BY discovered_datetime DESC ';
        $sql .= ' LIMIT 3 OFFSET 0 ';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * discovredテーブルから指定IDの一部データを取得
     *
     *  @param integer $page ページ番号
     *  @return array $result 全データ
     */
    public function select_discovered_list($page = 1, $creature_id) {
      $sql = 'SELECT * FROM discovered WHERE del_flg = 0 AND creature_id = :creature_id ORDER BY discovered_datetime DESC ';
      $sql .= ' LIMIT 20 OFFSET '.(20 * ($page -1) );//20
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
      $sth->execute();

      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      return $result;

    }



    public function select_discovered($creature_id) {
        $sql = 'SELECT * FROM discovered WHERE del_flg = 0 AND creature_id = :creature_id ';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function discovered_delete($del_id) {
        $sql = 'UPDATE discovered SET del_flg = 1 WHERE discovered_id = :del_id ';
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
