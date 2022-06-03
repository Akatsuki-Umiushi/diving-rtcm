<?php
require_once(ROOT_PATH .'/Models/Db.php');


class Goods extends Db {
  public function __construct($dbh = null) {
    parent::__construct($dbh);
  }



  public function good_toggle() {
    $creature_id = $_POST['creatureId'];
    $user_id = $_SESSION['login_user']['user_id'];

    // goodsテーブルから投稿IDとユーザーIDが一致したレコードを取得するSQL文
    $sql = 'SELECT * FROM goods WHERE creature_id = :creature_id AND user_id = :user_id';
    try {

      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
      $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $sth->execute();
      $count = $sth->rowCount();
      // レコードが1件でもある場合
      if(! empty($count)){
        // レコードを削除する
        $sql = 'DELETE FROM goods WHERE  creature_id = :creature_id AND user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();

        //creature_idのいいね件数を取得して返す
        $result = count($this->get_good($creature_id));
        return $result;
      }else{
        // レコードを挿入する
        $sql = 'INSERT INTO goods (creature_id, user_id) VALUES (:creature_id, :user_id)';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();

        //creature_idのいいね件数を取得して返す
        $result = count($this->get_good($creature_id));
        return $result;
      }

    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
  }

  /**
  * 指定したいいねを調べる
  * @param  int $creature_id
  * @return int $result
  */
  public function get_good($creature_id) {
    $sql = 'SELECT * FROM goods WHERE creature_id = :creature_id ';
    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }


  /**
  * いいねしているかどうか調べる
  * @param  int $creature_id
  * @param  int $user_id
  * @return int $result   1||0 いいねしていれば1、していなければ0
  */
  public function good_check($creature_id, $user_id) {
    $sql = 'SELECT count(*) FROM goods WHERE creature_id = :creature_id AND user_id = :user_id';
    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_COLUMN);
    return $result;
  }

  /**
  * 指定した生物IDのいいねを調べる
  * @param  int $creature_id
  * @return int $result
  */
  public function get_good_list($page = 1, $creature_id) {
    $sql = 'SELECT * FROM goods WHERE creature_id = :creature_id ';
    $sql .= ' LIMIT 20 OFFSET '.(20 * ($page -1) );
    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }


  /**
  * 指定したユーザーIDのいいねを調べる
  * @param  int $user_id
  * @return int $result
  */
  public function get_good_at_user_id($user_id) {
    $sql = 'SELECT * FROM goods WHERE user_id = :user_id ';
    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }



}
