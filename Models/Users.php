<?php
require_once(ROOT_PATH .'/Models/Db.php');


class Users extends Db {
  public function __construct($dbh = null) {
      parent::__construct($dbh);
  }

      /**
       * ユーザー新規登録
       *
       * @param  array $userData
       * @return bool
       */
      public function createUser($userData) {
        $sql = 'INSERT INTO users (email, password) VALUES (?, ?)';

        $arr = [];
        $arr[] = $userData['email'];
        $arr[] = password_hash($userData['password'], PASSWORD_DEFAULT);
      try {

        $sth = $this->dbh->prepare($sql);
        $sth->execute($arr);

        //ユーザー名をユーザー$idに変更
        $user = $this->nameCreate($userData);
        if ($user == false) {
          throw new \Exception("ユーザー名の設定に失敗しました。", 1);

        }

        return true;

      }catch(\Exception $e) {
        return false;

        }
      }

      /**
       * ユーザー名の自動設定
       *
       * @param  array $userData
       * @return bool
       */
      public function nameCreate($userData) {
        //ユーザーをemailから検索して取得
        $user = $this->getUserByEmail($userData['email']);
        $name = "ユーザー". $user['user_id'];

        $sql = 'UPDATE users SET name =:user_name';
        $sql .= ' WHERE email = :email';

        try {

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $userData['email'], PDO::PARAM_STR);
        $sth->bindParam(':user_name', $name, PDO::PARAM_STR);
        $sth->execute();


        return true;

      } catch(\Exception $e) {
        return false;

        }
      }



      /**
       * ログイン
       * @param string $email
       * @param string $password
       * @return bool $result
       */
      public function loginUser($email, $password) {
          //結果
            $result = false;
          //ユーザーをemailから検索して取得
            $user = $this->getUserByEmail($email);

            if ($user == false) {
              $_SESSION['error']['msg'] = 'メールアドレスまたはパスワードが一致しません。';
              return $result;//false
            }

          //パスワード照会
            if (password_verify($password, $user['password'])) {
              // ログイン成功
              session_regenerate_id(true);
              $_SESSION['login_user'] = $user;
              $result = true;
              return $result;
            }else {
              $_SESSION['error']['msg'] = 'メールアドレスまたはパスワードが一致しません。';
              return $result;//false
            }

      }




      /**
       * emailからユーザーを取得
       * @param string $email
       * @return array|bool $user|$result
       */
      public function getUserByEmail($email) {
        $sql = 'SELECT user_id, name, email, password, user_stop, admin FROM users ';
        $sql .= 'WHERE email = :email ';

        try {

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        return $user;

      } catch(\Exception $e) {
        return false;

        }
      }


      /**
       * ユーザーIDからユーザー情報を取得
       * @param string $user_id
       * @return array|bool $user|$result
       */
      public function get_user_by_Id($user_id) {
        $sql = 'SELECT * FROM users ';
        $sql .= 'WHERE user_id = :user_id ';

        try {

        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        return $user;

      } catch(\Exception $e) {
        return false;

        }
      }

      /**
       * ログインがされていない。またはセッションが切れているかを確認するメソッド
       *
       *
       * @return bool   $result
       *
       */
    public function loginCheck() {
      $today = date('Y-m-d H:i:s');

      if (! isset($_SESSION['login_user'])) {
        $_SESSION['error']['msg'] = 'ログインがされていない、またはセッションが切れています。<br>恐れ入りますが、ログインしてください。 ';
        return false;

      }elseif ($_SESSION['login_user']['user_stop'] != NULL) {

          if ($_SESSION['login_user']['user_stop'] > $today) {
            $_SESSION['error']['msg'] = 'このアカウントは停止されています。<br> 停止解除日'.$_SESSION['login_user']['user_stop'];
            return false;
          }
      }
        return true;
    }

    public function set_token(){
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
      $_SESSION['token'] = bin2hex(random_bytes(32));
      return $_SESSION['token'];
    }

    public function check_token(){
        if(! $token = filter_input(INPUT_POST, 'token')){
            echo "不正な処理が行なわれました。1";
            echo '<a href="../index.php">トップページへ戻る</a>';
            exit;
        }elseif (empty($_SESSION['token']) ) {
            echo "不正な処理が行なわれました。2";
            echo '<a href="../index.php">トップページへ戻る</a>';
            exit;
        }elseif ($token != $_SESSION['token']) {
            echo "不正な処理が行なわれました。3";
            echo '<a href="../index.php">トップページへ戻る</a>';
            exit;
        } {
            return true;
        }
    }



    /**
     * usersテーブルの内容をUPDATEする
     * プロフィール編集
     *
     * @param  array $post  $_POST
     * @param  string $path  画像のパス、画像の更新がない場合は0で値が来る
     * @return array || bool(false)
     */
    public function profile_update($post, $path = 0) {
      $user_id = $post['user_id'];
      $image = $path;
      $name = $post['name'];
      $self_introduction = $post['self_introduction'];
      $email = $post['email'];

      if ($path !== 0 && ! empty($post['password'])) {
        //画像もパスワードもあり
          $password = password_hash($post['password'], PASSWORD_DEFAULT);
          $sql = 'UPDATE users SET image = :image, name = :name, self_introduction = :self_introduction, email = :email, password = :password ';
      }elseif ($path !== 0 && empty($post['password'])) {
        //画像のみあり
          $sql = 'UPDATE users SET image = :image, name = :name, self_introduction = :self_introduction, email = :email ';

      }elseif ($path == 0 && ! empty($post['password'])) {
        //パスワードのみあり
          $password = password_hash($post['password'], PASSWORD_DEFAULT);
          $sql = 'UPDATE users SET name = :name, self_introduction = :self_introduction, email = :email, password = :password ';
      }else {
        //画像もパスワードもなし
          $sql = 'UPDATE users SET name = :name, self_introduction = :self_introduction, email = :email ';
      }

          $sql .= ' WHERE user_id = :user_id';

      try {
          $sth = $this->dbh->prepare($sql);
          $this->dbh-> beginTransaction();

          if ($path != 0) {
            $sth->bindParam(':image', $image, PDO::PARAM_STR);
          }

          if (! empty($post['password'])) {
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
          }

          $sth->bindParam(':name', $name, PDO::PARAM_STR);
          $sth->bindParam(':self_introduction', $self_introduction, PDO::PARAM_STR);
          $sth->bindParam(':email', $email, PDO::PARAM_STR);
          $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
          $sth->execute();

          $this->dbh->commit();
          return true;

          } catch (\PDOException $e) {
              $this->dbh->rollback();
              return false;
          }

    }


    /**
    * usersテーブルの内容をUPDATEする
    * アカウント停止
    *
    *
    * @return array || bool(false)
    */
    public function user_stop_update(){
      $user_id = $_POST['user_id'];
      $user_stop = $_POST['account_stop_period'];
      $sql = 'UPDATE users SET user_stop = :user_stop ';
      $sql .= ' WHERE user_id = :user_id';

      try {
        $sth = $this->dbh->prepare($sql);
        $this->dbh-> beginTransaction();

        $sth->bindParam(':user_stop', $user_stop, PDO::PARAM_STR);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();

        $this->dbh->commit();
        return true;

      } catch (\PDOException $e) {
        $this->dbh->rollback();
        echo $e->getMessage();
        return false;
      }

    }

    /**
     * ユーザー一覧を取得
     * @return array
     */
    public function select_users_list($page = 1) {
        $sql = 'SELECT * FROM users ORDER BY user_id ASC ';
        $sql .= ' LIMIT 10 OFFSET ' .(10 * ($page -1) );
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * ユーザー一覧の全件数を取得
     * @return array
     */
    public function users_count() {
        $sql = 'SELECT count(*) FROM users ';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        $result = $sth->fetch(PDO::FETCH_COLUMN);
        return $result;
    }



    public function pass_reset_sent($email) {
      $sql = 'SELECT * FROM password_resets WHERE email = :email';
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':email', $email, PDO::PARAM_STR);
      $sth->execute();
      $pass_reset_user = $sth->fetch(PDO::FETCH_ASSOC);

      if (! $pass_reset_user) {
        // $passwordResetUserがいなければ、仮登録としてテーブルにインサート
        $sql = 'INSERT INTO password_resets(email, token) VALUES(:email, :token)';
      } else {
        // 既にフロー中の$passwordResetUserがいる場合、tokenの再発行と有効期限のリセットを行う
        $sql = 'UPDATE password_resets SET token = :token WHERE email = :email';
      }

      // password reset token生成
      $passwordResetToken = bin2hex(random_bytes(32));

      // password_resetsテーブルへの変更とメール送信は原子性を保ちたいため、トランザクションを設置する
      // メール送信に失敗した場合は、パスワードリセット処理自体も失敗させる
      try {
        $this->dbh->beginTransaction();

        // ユーザーを仮登録
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':email', $email, PDO::PARAM_STR);
        $sth->bindValue(':token', $passwordResetToken, PDO::PARAM_STR);
        $sth->execute();

       mb_language("Japanese");
       mb_internal_encoding("UTF-8");

       $url = "http://localhost/pass_reset/pass_reset.php?token=". $passwordResetToken;

       $subject =  'パスワードリセット用URLをお送りします';

       $body = <<<EOD
           24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。
           {$url}
           EOD;

       $headers = "From : ダイビングリアルタイム生物マップ\n";

       $headers .= "Content-Type : text/plain";

       $isSent = mb_send_mail($email, $subject, $body, $headers);

       // メール送信まで成功したら、password_resetsテーブルへの変更を確定
       $this->dbh->commit();
       return true;

     } catch (\Exception $e) {
         $this->dbh->rollBack();
         return false;
     }
   }


   public function get_pass_reset_user($token) {
      $sql = 'SELECT * FROM password_resets WHERE token = :token';
      $sth = $this->dbh->prepare($sql);
      $sth->bindparam(':token', $token, \PDO::PARAM_STR);
      $sth->execute();
      $pass_reset_user = $sth->fetch(\PDO::FETCH_ASSOC);

      if ($pass_reset_user == false) {
          echo '無効なURLです';
          exit();
      }

      $token_period = (date('Y-m-d H:i:s', strtotime('-1 day')));

      if ($pass_reset_user['token_sent_at'] <  $token_period ) {
        echo '有効期限切れです';
        exit();
      }

      return $pass_reset_user;

   }


   public function pass_reset_update($post) {
     $password = password_hash($post['password'], PASSWORD_DEFAULT);
     $email = $post['email'];
     $token = $post['reset_token'];

         try {
        $this->dbh->beginTransaction();

        // 該当ユーザーのパスワードを更新
        $sql = 'UPDATE users SET password = :password WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':password', $password, PDO::PARAM_STR);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);
        $sth->execute();

        // 用が済んだので、パスワードリセットテーブルから削除
        $sql = 'DELETE FROM password_resets WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);
        $sth->execute();

        $this->dbh->commit();
        return true;

    } catch (\Exception $e) {
        $this->dbh->rollBack();
        echo $e->getMessage();
    }


  }






  }

     ?>
