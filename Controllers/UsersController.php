<?php
require_once(ROOT_PATH .'/Models/Users.php');
require_once(ROOT_PATH .'/Models/Validation.php');

class UsersController {
  private $request;  //リクエストパラメータ(GET,POST)
  private $Users;   //Usersモデル
  private $Validation;   //Validationモデル

  public function __construct() {
    //リクエストパラメータの取得
    $this->request['get'] = $_GET;
    $this->request['post'] = $_POST;

    //モデルオブジェクトの生成
    $this->Users = new Users();

    //別モデルとの連携
    $dbh = $this->Users->get_db_handler();
    $this->Validation = new Validation($dbh);

  }

  /**
   * htmlspecialcharsを短縮するための関数
   * @param  array $array
   * @return array $clean
   */
  public function h($array){
    $clean = $this->Validation->sanitize($array);
    return $clean;
  }



  public function registerValid() {
    if (empty($this->request['post'])) {
      echo '指定のパラメータが不正です。このページを表示できません。<br>';
      echo '<a href="../index.php">トップページへ戻る</a>';
      exit();
    }

    $error = $this->Validation->SignupValidation($this->request['post']);
      if (count($error) > 0) {
        //エラーがあった場合戻す
        $_SESSION['error'] = $error;
        header('Location: ../signup.php');
        return;
      }

    if (count($error) === 0) {
      //ユーザー登録
      $register = $this->Users->createUser($this->request['post']);
      if ($register == true) {
        return "新規登録が完了しました";
      }else {
        return "新規登録に失敗しました<br>もう一度登録し直してください。";
      }

    }

  }


  /**
   * ログイン処理。ログインされている場合（$_SESSION['login_user']が存在する状態）、この処理は行われない。
   *
   * @return array $_SESSION['error'] || $_SESSION['login_user']
   *               ログイン失敗時    || ログイン成功時
   */
  public function Login() {
    if (empty($this->request['post'])) {
      echo '指定のパラメータが不正です。このページを表示できません。<br>';
      echo '<a href="../index.php">トップページへ戻る</a>';
      exit();
    }

    if (! isset($_SESSION['login_user']) ) {
      //ログイン
      $error = $this->Validation->LoginValidation($this->request['post']);
        if (count($error) > 0) {
          //エラーがあった場合戻す
          $_SESSION['error'] = $error;
          header('Location: ../login.php');
          return;
        }

        //ログイン処理
        //この処理が成功すれば$_SESSION['login_user']にログインユーザー情報が保管される。
        $result = $this->Users->loginUser($this->request['post']['email'], $this->request['post']['password']);
        $today = date('Y-m-d H:i:s');


        //ログイン失敗時の処理
        if ($result == false) {
          header('Location: ../login.php');
          exit();
        }elseif ($_SESSION['login_user']['user_stop'] != NULL) {

            if ($_SESSION['login_user']['user_stop'] > $today) {
              $_SESSION['error']['msg'] = 'このアカウントは停止されています。<br> 停止解除日'.$_SESSION['login_user']['user_stop'];
              header('Location: ../login.php');
              exit() ;
            }

        } {
        //ログイン成功時の処理
           header('Location: ../index.php');
           exit();
        }

      }
  }

  /**
   * プロフィール編集
   *
   * @return
   */
  public function ProfileEdit() {
    if (empty($this->request['post'])) {
      echo '指定のパラメータが不正です。このページを表示できません。<br>';
      echo '<a href="../index.php">トップページへ戻る</a>';
      exit();
    }

    //バリデーション
    $error = $this->Validation->ProfileEditValidation($this->request['post']);


      if (count($error) > 0) {
          //エラーがあった場合戻す
          $_SESSION['error'] = $error;
          $_SESSION['post'] = $this->request['post'];
          if (! empty($_FILES['image']['tmp_name'])) {
            $_SESSION['post']['image'] = 'imageあり';
          }
          header('Location: ../profile_edit.php');
          return;
      }else {
        //画像の移動、パス取得
          if (! empty($_FILES['image']['tmp_name'])) {
            $path = $this->Validation->get_file_path('profile');
            if ($path == false ) {
              $_SESSION['post'] = $_POST;
              header('Location: ../post.php');
              return;
            }
          }else {
            $path = 0;
          }

          //DBへの登録
          $result = $this->Users->profile_update($this->request['post'], $path);

          if ($result == false) {
            $_SESSION['post'] = $_POST;
            header('Location: ../profile_edit.php');
            return;
          }else {
            $user = $this->Users->getUserByEmail($_SESSION['login_user']['email']);
            $_SESSION['login_user'] =  '';
            $_SESSION['login_user'] = $user ;
            return;
          }

      }

  }



  /**
  * アカウント停止
  *
  * @return
  */
  public function UserStop() {
    if (empty($this->request['post']['user_id']) || empty($this->request['post']['account_stop_period'])) {
      echo '指定のパラメータが不正です。このページを表示できません。<br>';
      echo '<a href="../index.php">トップページへ戻る</a>';
      exit();
    }


      //DBへの登録
      $result = $this->Users->user_stop_update();

      if ($result == true) {
        $stop_period = $_POST['account_stop_period'];
        return "アカウント停止が完了しました<br>停止解除日：". $stop_period;
      }else {
        return "アカウント停止が失敗しました<br>もう一度やり直してください。";
      }

    }


    /**
    * ユーザー一覧をID順で取得する
     *
     * @return
     */
  public function UsersList() {
      $page = 1;
      if (isset($this->request['get']['page']) && is_numeric($_GET['page'])) {
        $page = $this->request['get']['page'];
      } else {
        header('Location: ../users_list.php?page=1');
      }


      $users = $this->Users->select_users_list($page);
      $u_count = $this->Users->users_count();

      $max_page = ceil($u_count / 10);

      $from_record = ($page - 1) * 10 + 1;

      if ($page == $max_page && $u_count % 10 !== 0) {
        $to_record = ($page - 1) * 10 + $u_count % 10;
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
        'users' => $users,
        'page' => $page,
        'max' => $max_page,
        'count' => $u_count,
        'from' => $from_record,
        'to' => $to_record

      ];
      return $params;
    }



    public function pass_reset_sent_mail($value='') {
      if (! $email = filter_input(INPUT_POST, 'email')) {
        echo '指定のパラメータが不正です。このページを表示できません。<br>';
        echo '<a href="../index.php">トップページへ戻る</a>';
      }

      $user = $this->Users->getUserByEmail($email);

      if ($user == false) {
        return;
      }elseif ($user == true) {
        $pass_reset_insert = $this->Users->pass_reset_sent($email);
        return;
      }

    }


    public function use_get_pass_reset_user() {
      if (! $token = filter_input(INPUT_GET, 'token')) {
        echo 'URLが無効です。<br>';
        echo '<a href="../login.php">ログインページへ戻る</a>';
      }

      $user = $this->Users->get_pass_reset_user($token);

      return $user;
    }

    public function PassReset() {

      $error = $this->Validation->PassResetValid();

        if (count($error) > 0) {
            //エラーがあった場合戻す
            $_SESSION['error'] = $error;
            $reset_token = $_POST['reset_token'];

            header('Location: ./pass_reset.php?token='.$reset_token );
            exit();
        }else {
            $pass_reset = $this->Users->pass_reset_update($this->request['post']);
              if ($pass_reset == false) {
                return 'パスワードリセットに失敗しました';
              }else {
                return 'パスワードが変更されました';
              }

        }
    }





  /**
   * ユーザーIDからユーザー情報の取得
   *
   */
  public function use_get_user_by_id($user_id)  {
    $result = $this->Users->get_user_by_id($user_id);
    if ($result == false) {
       echo "ユーザー情報の取得に失敗しました。";
       echo "恐れ入りますが、もう一度やり直してください。";
       echo '<a href="../index.php">トップページへ戻る</a>';
       exit();
    }else {
      return $result;
    }
  }


/**
 * ログインされているか確認。ログインしていない、またはセッションが切れている場合はログインページへ遷移。
 *
 */
  public function use_loginCheck()  {
    $result = $this->Users->loginCheck();
    if ($result == false) {
       header('Location: ../login.php');
       exit();
    }
  }


  /**
   *トークンをセットする関数を呼び出す。
   */
  public function use_set_token(){
    $token = $this->Users->set_token();
    return $token;
  }

  /**
   *
   */
   public function use_check_token(){
     $result = $this->Users->check_token();
     if ($result == true) {
       unset($_SESSION['token']);
     }
     return ;
   }

   public function admin_check() {
     if ($_SESSION['login_user']['admin'] != 1) {
       echo "お探しのページは存在しません。 <br> ";
       echo '<a href="../index.php?page=1">トップページへ戻る</a>';
       exit();
     }
   }


}
 ?>
