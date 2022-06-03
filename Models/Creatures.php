<?php
require_once(ROOT_PATH .'/Models/Db.php');


class Creatures extends Db {
  public function __construct($dbh = null) {
      parent::__construct($dbh);
  }


  /**
   * creaturesテーブルからすべてのデータを取得
   *
   *  @param integer $page ページ番号
   *  @return array $result 全生物データ
   */
  public function findAll($page = 1):Array {
      $sql = 'SELECT creature_id, c.name AS name, c.image AS c_image, spot, point, discovery_datetime, t.image AS t_image FROM creatures c LEFT JOIN types t ON c.type_id = t.type_id';
      $sql .= ' WHERE del_flg = 0 ORDER BY discovery_datetime DESC';
      $sql .= ' LIMIT 10 OFFSET '.(10 * ($page -1) );
      $sth = $this->dbh->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      return $result;
  }

  /**
  * creaturesテーブルから全データ数を取得
  *
  *  @return int $count 全生物の件数
  */
  public function countAll():Int {
    $sql = 'SELECT count(*) AS count FROM creatures WHERE del_flg = 0 ';
    $sth = $this->dbh->prepare($sql);
    $sth->execute();
    $count = $sth->fetchColumn();
    return $count;
  }


  /**
   * creaturesテーブルから指定したユーザーIDのすべてのデータを取得
   *
   *  @param integer $page ページ番号
   *  @return array $result 全生物データ
   */
  public function my_creaturesAll($page = 1, $user_id) {
      $sql = 'SELECT creature_id, c.name AS name, c.image AS c_image, spot, point, discovery_datetime, t.image AS t_image FROM creatures c LEFT JOIN types t ON c.type_id = t.type_id ';
      $sql .= ' WHERE del_flg = 0 AND user_id = :user_id ORDER BY discovery_datetime DESC';
      $sql .= ' LIMIT 10 OFFSET '.(10 * ($page -1) );//10
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      return $result;
  }

  /**
   * creaturesテーブルから指定したユーザーIDのすべてのデータ件数を取得
   *
   *
   *  @return array $result 全生物データ
   */
  public function my_creatures_count($user_id) {
      $sql = 'SELECT count(*) FROM creatures WHERE del_flg = 0 AND user_id = :user_id ';
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetch(PDO::FETCH_COLUMN);
      return $result;
  }


  /**
   * creaturesテーブルから指定した生物IDのすべてのデータを取得
   *
   *  @param int $page ページ番号
   *  @param array $creatures_id ユーザーIDの配列
   *  @return array $result 全生物データ
   */
  public function my_good_creaturesAll($page = 1, $creatures_id) {
    $search = array();
    $sql = 'SELECT creature_id, c.name AS name, c.image AS c_image, spot, point, discovery_datetime, t.image AS t_image FROM creatures c LEFT JOIN types t ON c.type_id = t.type_id ';
    $sql .= ' WHERE del_flg = 0 ';

    //creatures_idが複数の場合
    if (count($creatures_id) > 1) {
      $inClause_creature = substr(str_repeat(',?', count($creatures_id)), 1);
      $sql .= " AND c.creature_id IN({$inClause_creature}) ";
      foreach ($creatures_id as $value) {
        array_push($search, $value);
      }
    }else {
    // creatures_idが一つの場合
      $sql .= " AND c.creature_id = ? ";
      array_push($search, $creatures_id['0']);
    }

      $sql .= ' ORDER BY discovery_datetime DESC';
      $sql .= ' LIMIT 10 OFFSET '.(10 * ($page -1) );//10
      $sth = $this->dbh->prepare($sql);
      $sth->execute($search);
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
      return $result;
  }

  /**
   * creaturesテーブルから指定した生物IDのすべてのデータ件数を取得
   *
   *
   *  @return array $result 全生物データ
   */
  public function my_good_creatures_count($creatures_id) {
    $search = array();
    $sql = 'SELECT count(*) FROM creatures ';
    $sql .= ' WHERE del_flg = 0 ';

    //creatures_idが複数の場合
    if (count($creatures_id) > 1) {
      $inClause_creature = substr(str_repeat(',?', count($creatures_id)), 1);
      $sql .= " AND creature_id IN({$inClause_creature}) ";
      foreach ($creatures_id as $value) {
        array_push($search, $value);
      }
    }else {
    // creatures_idが一つの場合
      $sql .= " AND creature_id = ? ";
      array_push($search, $creatures_id['0']);
    }

      $sth = $this->dbh->prepare($sql);
      $sth->execute($search);
      $result = $sth->fetch(PDO::FETCH_COLUMN);
      return $result;
  }



  /**
   * creaturesテーブルから検索条件に合うデータを取得
   *
   *  @param integer $page ページ番号
   *  @return array $result 全選手データ
   */
  public function search($page = 1):Array {
    $name = $_GET['name'];
    $type_id = $_GET['type_id'];
    $spot = $_GET['spot'];
    $point = $_GET['point'];
    $color_id = $_GET['color_id'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $search = array(0);
    array_shift($type_id);
    array_shift($color_id);


    $sql = "SELECT DISTINCT c.creature_id AS creature_id, c.name AS name, c.image AS c_image, spot, point, discovery_datetime, t.image AS t_image FROM creatures c LEFT JOIN types t ON c.type_id = t.type_id LEFT JOIN creature_colors cc ON c.creature_id = cc.creature_id ";
    $sql .= ' WHERE del_flg = 0 ';

    //nameがある場合
    if ($name != NULL) {
      $sql .= ' AND c.name = ? ';
      array_push($search, $name);
    }

    //type_idがある場合
    if ($type_id != NULL) {
      //type_idが複数の場合
      if (count($type_id) > 1) {
        $inClause_type = substr(str_repeat(',?', count($type_id)), 1);
        $sql .= " AND t.type_id IN ({$inClause_type}) ";
        foreach ($type_id as $key => $value) {
          array_push($search, $value);
        }
      }else {
      // type_idが一つの場合
        $sql .= " AND t.type_id = ? ";
        array_push($search, $type_id['0']);
      }

    }
    //spotがある場合
    if ($spot != NULL) {
      $sql .= " AND spot = ? ";
      array_push($search, $spot);
    }
    //pointがある場合
    if ($point != NULL) {
      $sql .= " AND point = ? ";
      array_push($search, $point);
    }
    //color_idがある場合
    if ($color_id != NULL) {
      //color_idが複数の場合
      if (count($color_id) > 1) {
        $inClause_color = substr(str_repeat(',?', count($color_id)), 1);
        $sql .= " AND cc.color_id IN({$inClause_color}) ";
        foreach ($color_id as $key => $value) {
          array_push($search, $value);
        }
      }else {
      // color_idが一つの場合
        $sql .= " AND cc.color_id = ? ";
        array_push($search, $color_id['0']);
      }


    }
    if ($start_date != NULL && $end_date != NULL) {
      $sql .= " AND discovery_datetime BETWEEN ? AND ? ";
      array_push($search, $start_date, $end_date);

    }elseif ($start_date != NULL && $end_date == NULL) {
      $sql .= " AND discovery_datetime BETWEEN ? AND '9999-12-31' ";
      array_push($search, $start_date);

    }elseif ($start_date == NULL && $end_date != NULL) {
      $sql .= " AND discovery_datetime BETWEEN '2000-01-01' AND ? ";
      array_push($search, $end_date);

    }

    $sql .= ' ORDER BY discovery_datetime DESC';
    $sql .= ' LIMIT 10 OFFSET '.(10 * ($page - 1) );//10


    array_shift($search);



    $sth = $this->dbh->prepare($sql);

    if ($search == NULL) {
      $sth->execute();
    }else {
      $sth->execute($search);
    }

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;

  }





  /**
   * creaturesテーブルから指定した検索データの全件数を取得
   *
   *  @return int $count 全生物の件数
   */
  public function count_search():Int {
    $name = $_GET['name'];
    $type_id = $_GET['type_id'];
    $spot = $_GET['spot'];
    $point = $_GET['point'];
    $color_id = $_GET['color_id'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $search = array(0);
    array_shift($type_id);
    array_shift($color_id);


    $sql = "SELECT DISTINCT c.creature_id AS creature_id, c.name AS name, c.image AS c_image, spot, point, discovery_datetime, t.image AS t_image FROM creatures c LEFT JOIN types t ON c.type_id = t.type_id LEFT JOIN creature_colors cc ON c.creature_id = cc.creature_id ";
    $sql .= " WHERE del_flg = 0 ";

    //nameがある場合
    if ($name != NULL) {
      $sql .= " AND c.name = ? ";
      array_push($search, $name);
    }
    //type_idがある場合
    if ($type_id != NULL) {
      //type_idが複数の場合
      if (count($type_id) > 1) {
        $inClause_type = substr(str_repeat(',?', count($type_id)), 1);
        $sql .= " AND t.type_id IN ({$inClause_type}) ";
        foreach ($type_id as $key => $value) {
            array_push($search, $value);
          }
      }else {
      // type_idが一つの場合
        $sql .= " AND t.type_id = ? ";
        array_push($search, $type_id['0']);
      }
    }
    //spotがある場合
    if ($spot != NULL) {
      $sql .= " AND spot = ? ";
      array_push($search, $spot);
    }
    //pointがある場合
    if ($point != NULL) {
      $sql .= " AND point = ? ";
      array_push($search, $point);
    }
    //color_idがある場合
    if ($color_id != NULL) {
      //color_idが複数の場合
      if (count($color_id) > 1) {
        $inClause_color = substr(str_repeat(',?', count($color_id)), 1);
        $sql .= " AND cc.color_id IN ({$inClause_color}) ";
        foreach ($color_id as $key => $value) {
          array_push($search, $value);
        }
      }else {
      // color_idが一つの場合
        $sql .= " AND cc.color_id = ? ";
        array_push($search, $color_id['0']);
      }
    }
    //start_dateとend_dateがある場合
    if ($start_date != NULL && $end_date != NULL) {
      $sql .= " AND discovery_datetime BETWEEN ? AND ? ";
      array_push($search, $start_date, $end_date);

    }elseif ($start_date != NULL && $end_date == NULL) {
      //start_dateがある場合
      $sql .= " AND discovery_datetime BETWEEN ? AND '9999-12-31' ";
      array_push($search, $start_date);

    }elseif ($start_date == NULL && $end_date != NULL) {
      //end_dateがある場合
      $sql .= " AND discovery_datetime BETWEEN '2000-01-01' AND ? ";
      array_push($search, $end_date);

    }


    //searchの先頭の空要素を排除
    array_shift($search);

    $sth = $this->dbh->prepare($sql);

    if ($search == NULL) {
      $sth->execute();
      $count = $sth -> rowCount();
    }else {
      $sth->execute($search);
      $count = $sth -> rowCount();
    }
     return $count;
  }




  /**
   * 投稿内容をcreaturesテーブルに登録
   * @param  array $post               [description]
   * @param  string $path               [description]
   * @return [type]       [description]
   */
  public function insert_creature($post, $path) {
        $user_id = $post['user_id'];
        $image = $path;
        $name = $post['name'];
        $type_id = $post['type_id'];
        $spot = $post['spot'];
        $point = $post['point'];
        $color_id = $post['color_id'];
        $discovery_datetime = $post['discovery_date'].' '.$post['discovery_time'];
        $body = $_POST['body'];

        $sql = 'INSERT INTO creatures (user_id, image, name, type_id, spot, point, discovery_datetime, body) VALUES (:user_id, :image, :name, :type_id, :spot, :point, :discovery_datetime, :body)';


    try {

        $this->dbh-> beginTransaction();

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->bindParam(':image', $path, PDO::PARAM_STR);
        $sth->bindParam(':name', $name, PDO::PARAM_STR);
        $sth->bindParam(':type_id', $type_id, PDO::PARAM_INT);
        $sth->bindParam(':spot', $spot, PDO::PARAM_STR);
        $sth->bindParam(':point', $point, PDO::PARAM_STR);
        $sth->bindParam(':discovery_datetime', $discovery_datetime, PDO::PARAM_STR);
        $sth->bindParam(':body', $body, PDO::PARAM_STR);
        $sth->execute();

        $creature = $sth->fetch(PDO::FETCH_ASSOC);

        $creature_id = $this->dbh->lastInsertId();

        if (count($color_id) > 1) {

          $insert_color = $this->insert_creature_color($creature_id, $color_id);

          if ($insert_color == false) {
            $_SESSION['error']['msg'] = "カラーの登録に失敗しました。";
            throw new \Exception("カラーの登録に失敗しました。");
          }
        }

        $this->dbh->commit();

        return $creature_id;

    }catch(\Exception $e) {
        $this->dbh->rollback();
        echo $e->getMessage();
        $_SESSION['error']['msg'] = "データベースへの登録に失敗しました。
        もう一度やり直してください。";
        return false;

    }
  }



  /**
   * creature_colorテーブルに登録する
   * @param   int    $id     生物ID
   * @param   array  $ color_id    カラーID
   * @return  bool
   */
  public function insert_creature_color($creature_id, $color_id) {
    //先頭についている0を取り除く
    array_shift($color_id);

    foreach ($color_id as $key => $value) {
          $sql = 'INSERT INTO creature_colors (creature_id, color_id) VALUES (:creture_id, :color_id)';

          try {

            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':creture_id', $creature_id, PDO::PARAM_INT);
            $sth->bindParam(':color_id', $value, PDO::PARAM_INT);

            $sth->execute();

          }catch(\Exception $e) {
            return false;

            }

    }
    //エラーなしであればtrue
    return true;

  }



    /**
     * creaturessテーブルの内容をUPDATEする
     *
     * @param  array $post  $_POST
     * @param  string $path  画像のパス、画像の更新がない場合は0で値が来る
     * @return bool
     */
    public function creature_update($post, $path = 0) {
      $creature_id = $post['creature_id'];
      $user_id = $post['user_id'];
      $image = $path;
      $name = $post['name'];
      $type_id = $post['type_id'];
      $spot = $post['spot'];
      $point = $post['point'];
      $color_id = $post['color_id'];
      $discovery_datetime = $post['discovery_date'].' '.$post['discovery_time'];
      $body = $post['body'];

      if ($path == 0 ) {
          $sql = 'UPDATE creatures SET name = :name, type_id = :type_id, spot = :spot, point = :point, discovery_datetime = :discovery_datetime, body = :body';
      }else {
          $sql = 'UPDATE creatures SET image = :image, name = :name, type_id = :type_id, spot = :spot, point = :point, discovery_datetime = :discovery_datetime, body = :body';
      }
          $sql .= ' WHERE creature_id = :creature_id';
          $sth = $this->dbh->prepare($sql);
          $this->dbh-> beginTransaction();

          try {
              if ($path != 0) {
                $sth->bindParam(':image', $image, PDO::PARAM_STR);
              }

              $sth->bindParam(':name', $name, PDO::PARAM_STR);
              $sth->bindParam(':type_id', $type_id, PDO::PARAM_INT);
              $sth->bindParam(':spot', $spot, PDO::PARAM_STR);
              $sth->bindParam(':point', $point, PDO::PARAM_STR);
              $sth->bindParam(':discovery_datetime', $discovery_datetime, PDO::PARAM_STR);
              $sth->bindParam(':body', $body, PDO::PARAM_STR);
              $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
              $sth->execute();

              if (count($color_id) > 1) {
                  $update_color = $this->update_creature_color($creature_id, $color_id);

                  if ($update_color == false) {
                    $_SESSION['error']['msg'] = "カラーの更新に失敗しました。";
                    //throw new \Exception("カラーの更新に失敗しました。");
                  }
              }

              $this->dbh->commit();
              return true;

              } catch (\PDOException $e) {
                  $this->dbh->rollback();
                  throw $e;
                  return false;
              }

    }


    /**
     * creature_colorテーブルの値を削除、再登録する
     * @param   int    $id     生物ID
     * @param   array  $ color_id    カラーID
     * @return  bool
     */
    public function update_creature_color($creature_id, $color_id) {

      //DELETE
      $sql = 'DELETE FROM creature_colors WHERE creature_id = :creature_id';
      $sth = $this->dbh->prepare($sql);

      try {
          $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
          $sth->execute();

          $insert_color = $this->insert_creature_color($creature_id, $color_id);
          if ($insert_color == false) {
            throw new \Exception("カラーの登録に失敗しました。");
          }else {
            return true;
          }


      } catch (\Exception $e) {
          $this->dbh->rollback();
          return false;
      }
      //エラーなしであればtrue
      return true;

    }

    /**
     * creaturesテーブルから指定したIDのデータを削除
     *
     * @param  integer $del_id 削除するID
     * @return bool
     */
    public function creature_delete($del_id = 0) {
          $sql = 'UPDATE creatures SET del_flg = 1';
          $sql .= ' WHERE creature_id = :del_id';
          $sth = $this->dbh->prepare($sql);
          try {

            $sth->bindParam(':del_id', $del_id, PDO::PARAM_INT);
            $sth->execute();

            return true;

          } catch (\Exception $e) {
            return false;
          }

    }




  /**
   * creaturesテーブルから指定したIDの値を取得
   * @param  int $creature_id
   * @return array $result
   */
  public function get_creature($creature_id) {
    $sql = 'SELECT * FROM creatures ';
    $sql .= 'WHERE del_flg = 0 ';
    $sql .= 'AND creature_id = :creature_id';

    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    return $result;

  }

  /**
   * typesテーブルからIDで指定した行の値を取得
   * @param  int $type_id
   * @return array $result
   */
  public function get_type($type_id) {
    $sql = 'SELECT * FROM types ';
    $sql .= 'WHERE type_id = :type_id ';

    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':type_id', $type_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if ($result == NULL) {
      $result = array('name' => "");;
    }
    return $result;

  }

  /**
   * creature_colorsテーブルからcreature_idで指定したcolor_idの値を取得
   * @param  int $type_id
   * @return array $result
   */
  public function get_color_id($creature_id) {
    $sql = 'SELECT color_id FROM creature_colors ';
    $sql .= 'WHERE creature_id = :creature_id ';

    $sth = $this->dbh->prepare($sql);
    $sth->bindParam(':creature_id', $creature_id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_COLUMN);
    return $result;

  }

  /**
   * colorsテーブルから指定したcolor_idの名前を取得
   * @param  int||array $color_id
   * @return int||array $result
   */
  public function get_color_name($color_id) {
    if ($color_id != NULL) {
      if (is_array($color_id)) {
        $inClause = substr(str_repeat(',?', count($color_id)), 1);
      }else {
        $inClause = '?';
      }

      $sql = 'SELECT name FROM colors';
      $sql .= ' WHERE color_id IN ('. $inClause. ') ORDER BY color_id ASC';

      $sth = $this->dbh->prepare($sql);
      $sth->execute($color_id);
      $result = $sth->fetchAll(PDO::FETCH_COLUMN);

      return $result;
    }

  }


  /**
   * datetimeから(YYYY-mm-dd)と(H:i)の配列名を取得
   * @param  int||array $datetime
   * @return int||array $result
   */
  public function get_date_time($datetime) {
    list($year, $month, $day, $hour, $minute, $second) = preg_split('/[-: ]/', $datetime);
    $date = $year.'-'.$month.'-'.$day;
    $time = $hour.':'.$minute;
    $params = [
      'date' => $date,
      'time' => $time
    ];

    return $params;

  }







}
?>
