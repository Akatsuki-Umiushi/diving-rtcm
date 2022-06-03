<?php
require_once(ROOT_PATH .'/Models/Db.php');



class validation extends Db {

  public $errors; // エラーメッセージの配列


  public function __construct($dbh = null) {
    parent::__construct($dbh);

  }


    /**
     *  htmlspecialcharsを短縮するための関数
     *
     * @param  array $clean htmlspecialcharsをする配列または変数
     * @return array $h     htmlspecialcharsをした配列または変数
     */
      public function sanitize($clean) {
          if (is_array($clean)) {
              return array_map('Validation::sanitize', $clean);
          } else {
              return htmlspecialchars($clean, ENT_QUOTES);
          }

      }


    /**
     * ユーザー新規登録のバリデーション
     *
     * @return $errors array //エラーメッセージ
     */
    public function SignupValidation() {
       $this->errors = array();//初期化

       if (! $email = filter_input(INPUT_POST, 'email')) {
           $this->errors['email'] = 'メールアドレスを入力してください。';
         }elseif (! preg_match("/^.+@{1}.+$/" , $email)) {
           $this->errors['email'] = 'メールアドレスは@を含めた形で入力してください。';
         }else {
           $sql = "SELECT email FROM users";
           $sth = $this->dbh->query($sql);
           $emails = $sth->fetchAll(PDO::FETCH_COLUMN);
           foreach ($emails as $value) {
               if ($value == $email ) {
                 $this->errors['email'] = 'このメールアドレスは使用されています。';
             }
         }
       }

      if(! $password = filter_input(INPUT_POST, 'password')) {
        $this->errors['password'] = 'パスワードを入力してください。';

      }elseif (! preg_match("/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z\d]{8,}$/", $password)) {
        $this->errors['password'] = 'パスワードは英数字を含む8文字以上で入力してください。' ;
      }

      if (! $password_conf = filter_input(INPUT_POST, 'password_conf')) {
        $this->errors['password_conf'] = 'パスワード（確認）を入力してください。';
      }elseif ($password !== $password_conf) {
        $this->errors['password_conf'] = 'パスワードとパスワード（確認）が異なっています。' ;
      }


      return $this->errors;

      }



    /**
     * ログイン時のバリデーション
     *
     * @return $errors array //エラーメッセージ
     */
    public function LoginValidation() {
       $this->errors = array();//初期化

       if (! $email = filter_input(INPUT_POST, 'email')) {
         $this->errors['email'] = 'メールアドレスを入力してください。';
       }
       if (! $password = filter_input(INPUT_POST, 'password')) {
         $this->errors['password'] = 'パスワードを入力してください。';
       }

      return $this->errors;

      }

      public function PassResetValid() {
        $this->errors = array();//初期化

        if(! $password = filter_input(INPUT_POST, 'password')) {
          $this->errors['password'] = 'パスワードを入力してください。';

        }elseif (! preg_match("/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z\d]{8,}$/", $password)) {
          $this->errors['password'] = 'パスワードは英数字を含む8文字以上で入力してください。' ;
        }

        if (! $password_conf = filter_input(INPUT_POST, 'password_conf')) {
          $this->errors['password_conf'] = 'パスワード（確認）を入力してください。';
        }elseif ($password !== $password_conf) {
          $this->errors['password_conf'] = 'パスワードとパスワード（確認）が異なっています。' ;
        }

        if (! $email = filter_input(INPUT_POST, 'email')) {
          $this->errors['email'] = 'error' ;
        }
        if (! $token = filter_input(INPUT_POST, 'token')) {
          $this->errors['token'] = 'error' ;
        }
        if (! $reset_token = filter_input(INPUT_POST, 'reset_token')) {
          $this->errors['reset_token'] = 'error' ;
        }

        return $this->errors;
      }


      /**
       * プロフィール編集のバリデーション
       *
       * @return $errors array //エラーメッセージ
       */
      public function ProfileEditValidation() {
         $this->errors = array();//初期化

       //ImageValid
           if (! empty($_FILES['image']['tmp_name'])) {
             if (is_uploaded_file($_FILES['image']['tmp_name'])) {

               $image_result = $this->ImageValid();
               if ($image_result !== true) {
                 //バリデーションにエラーがあったらエラー配列に格納
                 $this->errors['image'] = $image_result;
               }

             }else {
               //postで送信されていない場合
               $this->errors['image'] = '送信方法が正しくありません。恐れ入りますが、もう一度やり直してください。';
             }
           }

       //nameValid
           if(! $name = filter_input(INPUT_POST, 'name')) {
             //未入力チェック
             $this->errors['name'] = 'ユーザー名を入力してください。';

           } elseif (mb_strlen($name) > 20) {
               //文字数チェック
               $this->errors['name'] = 'ユーザー名は20文字以内で入力してください。' ;
           }

       //self_introductionValid
           if($self_introduction = filter_input(INPUT_POST, 'self_introduction')) {
              if (mb_strlen($self_introduction) > 200) {
                 //文字数チェック
                 $this->errors['self_introduction'] = '自己紹介は200文字以内で入力してください。' ;
               }
           }


       //emailValid
           if (! $email = filter_input(INPUT_POST, 'email')) {
               $this->errors['email'] = 'メールアドレスを入力してください。';
             }elseif (! preg_match("/^.+@{1}.+$/" , $email)) {
               $this->errors['email'] = 'メールアドレスは＠を含めた形で入力してください。';
             }elseif ($_SESSION['login_user']['email'] != $email) {
               $sql = "SELECT email FROM users";
               $sth = $this->dbh->query($sql);
               $emails = $sth->fetchAll(PDO::FETCH_COLUMN);
               foreach ($emails as $value) {
                 if ($value == $email ) {
                   $this->errors['email'] = 'このメールアドレスは使用されています。';
                 }
               }
           }


       //passwordValid
           if($password = filter_input(INPUT_POST, 'password')) {
             if (! preg_match("/^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z\d]{8,}$/", $password)) {
               $this->errors['password'] = 'パスワードは英数字を含む8文字以上で入力してください。';
             }
           }

           if ($password_conf = filter_input(INPUT_POST, 'password_conf')) {
             if ($password !== $password_conf) {
               $this->errors['password_conf'] = 'パスワードとパスワード（確認）が異なっています。';
             }
           }


         return $this->errors;

       }






      public function PostValidation()  {
          $this->errors = array();//初期化


        //ImageValid
              if (! empty($_FILES['image']['tmp_name'])) {
                if (is_uploaded_file($_FILES['image']['tmp_name'])) {

                  $image_result = $this->ImageValid();
                  if ($image_result !== true) {
                    //バリデーションにエラーがあったらエラー配列に格納
                    $this->errors['image'] = $image_result;
                  }
                }else {
                  //postで送信されていない場合
                  $this->errors['image'] = '送信方法が正しくありません。恐れ入りますが、もう一度やり直してください。';
                }
              }


        //nameValid
            if(! $name = filter_input(INPUT_POST, 'name')) {
              //未入力チェック
              $this->errors['name'] = '生物の名前を入力してください。';

            }elseif (mb_strlen($name) > 20) {
              //文字数チェック
              $this->errors['name'] = '生物の名前は20文字以内で入力してください。' ;
            }


        //typeValid
            if(! $type_id = filter_input(INPUT_POST, 'type_id')) {
              //未選択チェック
              $this->errors['type_id'] = '生物の種類を選択してください。';
            }elseif ($type_id > 13 || $type_id < 1) {
                  $this->errors['type_id'] = 'その生物は存在しません。正しく選択してください。';
            }


        //spotValid
            if(! $spot = filter_input(INPUT_POST, 'spot')) {
              //未入力チェック
              $this->errors['spot'] = 'ダイビングスポットを入力してください。';

            }elseif (mb_strlen($spot) > 20) {
              //文字数チェック
              $this->errors['spot'] = 'ダイビングスポットは20文字以内で入力してください。' ;
            }


        //pointValid
            if($point = filter_input(INPUT_POST, 'point')) {
              if (mb_strlen($point) > 20) {
                //文字数チェック
                $this->errors['point'] = 'ポイントは20文字以内で入力してください。' ;
            }
          }


        //discovery_datetimeValid
            if(! $discovery_date = filter_input(INPUT_POST, 'discovery_date')) {
              //未入力チェック
              $this->errors['discovery_date'] = '日付を入力してください。';

            }elseif (preg_match('/^[0-9]{4}\/{1}[0-9]{1,2}\/{1}[0-9]{1,2}$/', $discovery_date)){
                //区切り文字が「/」かをチェック

                //「/」であれば存在する日付かをチェック

                //区切り文字とフォーマットの指定
                $delimiter = '/';
                $format = 'Y'.$delimiter.'m'.$delimiter.'d';

                $date_result = $this->date_check($delimiter,$discovery_date);
                if ($date_result == false) {
                  $this->errors['discovery_date'] = '存在しない日付です。';
                }

            }elseif (preg_match('/^[0-9]{4}-{1}[0-9]{1,2}-{1}[0-9]{1,2}$/', $discovery_date)) {
                //「-」であれば存在する日付かをチェック

                //区切り文字とフォーマットの指定
                $delimiter = '-';
                $format = 'Y'.$delimiter.'m'.$delimiter.'d';

                $date_result = $this->date_check($delimiter,$discovery_date);
                if ($date_result == false) {

                  $this->errors['discovery_date'] = '存在しない日付です。';

                }

            }else {
                //どちらでもなければエラー
                $this->errors['discovery_date'] = '日付の入力形式が正しくありません。"YYYY/mm/dd"または"YYYY-mm-dd"のどちらかの形式で入力してください。';
            }


            if (! empty($format)) {
                if (date($format) < $discovery_date) {
                  //未来の日付チェック
                  $this->errors['discovery_date'] = '未来の日付が入力されています。現在、または過去の日付を選択してください。';

                }elseif (date($format) == $discovery_date) {
                  //当日だった場合時刻チェック
                    if($discovery_time = filter_input(INPUT_POST, 'discovery_time')) {
                        if (preg_match('/^[0-9]{1,2}:{1}[0-9]{1,2}$/', $discovery_time)) {
                          //[H:i]の形式であれば存在する時間かをチェック
                          $time_result = $this->time_check($discovery_time);
                          if ($time_result == false) {
                            $this->errors['discovery_time'] = '存在しない時刻です。';
                          }

                        }else{
                            $this->errors['discovery_date'] = '時刻の入力形式が正しくありません。"01:23"または"1:23"のどちらかの形式で入力してください。';

                        }

                        if (date('H:i') < $discovery_time ) {
                          //未来の時刻チェック
                          $this->errors['discovery_time'] = '未来の時刻が入力されています。現在、または過去の時刻を選択してください';
                        }
                    }
                }

        //colorValid

            if($color_id = filter_input(INPUT_POST, 'color_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY)) {
              array_shift($color_id);
              foreach ($color_id as $key => $value) {
                if ($value > 11 || $value < 1) {
                  $this->errors['color_id'] = '存在しない値が選択されています。正しく選択してください。';
                }
              }
            }



        //bodyValid
            if($point = filter_input(INPUT_POST, 'point')) {
              if (mb_strlen($point) > 1000) {
                //文字数チェック
                $this->errors['body'] = '詳細コメントは1000文字以内で入力してください。' ;
              }
            }

      }

      return $this->errors;

      }



      public function MitsuketeValidation()  {
          $this->errors = array();//初期化

          //discovery_datetimeValid
              if(! $discovery_date = filter_input(INPUT_POST, 'discovery_date')) {
                //未入力チェック
                $this->errors['discovery_date'] = '日付を入力してください。';

              }elseif (preg_match('/^[0-9]{4}\/{1}[0-9]{1,2}\/{1}[0-9]{1,2}$/', $discovery_date)){
                  //区切り文字が「/」かをチェック

                  //「/」であれば存在する日付かをチェック

                  //区切り文字とフォーマットの指定
                  $delimiter = '/';
                  $format = 'Y'.$delimiter.'m'.$delimiter.'d';

                  $date_result = $this->date_check($delimiter,$discovery_date);
                  if ($date_result == false) {
                    $this->errors['discovery_date'] = '存在しない日付です。';
                  }

              }elseif (preg_match('/^[0-9]{4}-{1}[0-9]{1,2}-{1}[0-9]{1,2}$/', $discovery_date)) {
                  //「-」であれば存在する日付かをチェック

                  //区切り文字とフォーマットの指定
                  $delimiter = '-';
                  $format = 'Y'.$delimiter.'m'.$delimiter.'d';

                  $date_result = $this->date_check($delimiter,$discovery_date);
                  if ($date_result == false) {

                    $this->errors['discovery_date'] = '存在しない日付です。';

                  }

              }else {
                  //どちらでもなければエラー
                  $this->errors['discovery_date'] = '日付の入力形式が正しくありません。"YYYY/mm/dd"または"YYYY-mm-dd"のどちらかの形式で入力してください。';
              }


              if (! empty($format)) {
                  if (date($format) < $discovery_date) {
                    //未来の日付チェック
                    $this->errors['discovery_date'] = '未来の日付が入力されています。現在、または過去の日付を選択してください。';

                  }elseif (date($format) == $discovery_date) {
                    //当日だった場合時刻チェック
                      if($discovery_time = filter_input(INPUT_POST, 'discovery_time')) {
                          if (preg_match('/^[0-9]{1,2}:{1}[0-9]{1,2}$/', $discovery_time)) {
                            //[H:i]の形式であれば存在する時間かをチェック
                            $time_result = $this->time_check($discovery_time);
                            if ($time_result == false) {
                              $this->errors['discovery_time'] = '存在しない時刻です。';
                            }

                          }else{
                              $this->errors['discovery_date'] = '時刻の入力形式が正しくありません。"01:23"または"1:23"のどちらかの形式で入力してください。';

                          }

                          if (date('H:i') < $discovery_time ) {
                            //未来の時刻チェック
                            $this->errors['discovery_time'] = '未来の時刻が入力されています。現在、または過去の時刻を選択してください';
                          }
                      }
                  }
              }

              return $this->errors;
        }


        public function CommentValidation() {
            $this->errors = array();//初期化

          //commentValid
              if(! $comment = filter_input(INPUT_POST, 'comment')) {
                //未入力チェック
                $this->errors['comment'] = 'コメントを入力してください。';

              } elseif (mb_strlen($comment) > 200) {
                  //文字数チェック
                  $this->errors['comment'] = 'コメントは200文字以内で入力してください。' ;
              }

              return  $this->errors;
        }



        /**
         * 送信されたファイルのバリデーション
         * @return string||bool (true) エラー時はエラー内容を返す。問題無ければtrue
         *
         */
        public function ImageValid() {
          $error = "";//初期化


          $file = $_FILES['image'];
          $file_name = basename($file['name']);
          $file_err = $file['error'];
          $file_size = $file['size'];

                    //拡張子は画像形式か？
                    $allow_ext = array('jpg', 'jpeg', 'png');
                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

                    if (! in_array(strtolower($file_ext), $allow_ext)) {
                      $error = "ファイルは画像形式( jpg / jpeg / png )で添付してください。";
                      return $error;
                    }

                    //ファイルサイズの確認
                    if ($file_size > 1048576*5 || $file_err == 2) {
                      $error = "ファイルサイズは5MB以内にしてください。";
                      return $error;
                    }

          return true;
        }


        /**
         * 送信されたファイルの移動
         * @param string $save_location  creatures||profileで指定
         *
         */
        public function get_file_path($save_location) {
          $file = $_FILES['image'];
          $file_name = basename($file['name']);
          $file_err = $file['error'];
          $tmp_path = $file['tmp_name'];
          $upload_dir = 'C:\xampp\htdocs\7_PHP自作\public/upload/image/'. $save_location. '/';
          $save_file_name =  'user_id='.$_SESSION['login_user']['user_id'].'_'.date('YmdHis'). $file_name;
          $save_path = $upload_dir. $save_file_name;


              //画像がアップロードされて無ければnoimage.pngのパスを渡す
              if (! is_uploaded_file($tmp_path)) {
                $result = "../img/icon/noimage.png";
                return $result;
              }


              //画像をuploadファイルに移動
              if (move_uploaded_file($tmp_path, $save_path)){
                //絶対パスから相対パスに置換
                $result = str_replace('C:\xampp\htdocs\7_PHP自作\public', '..', $save_path);
                return $result;
              }else {
                $_SESSION['error']['msg'] = '画像の登録に失敗しました。恐れ入りますが、もう一度やり直してください。';
                return false;
              }
        }




        /**
         * 日付の正確性バリデーション
         * @param string  $delimiter
         * @param string  $date
         * @return bool
         */
        public function date_check($delimiter, $date) {

          list($year, $month, $day) = explode($delimiter, $date);

          if(checkdate($month, $day, $year) == false) {
              return false;
          } else {
              return true;
          }
        }


        /**
         * 時刻のバリデーション
         *
         * @param string $time 時刻の文字列(フォーマットは"H:i")
         * @return bool 有効/無効
         */
        function time_check($time) {
          $timeObj = DateTime::createFromFormat('H:i', $time);

          if ($timeObj && $timeObj->format('H:i') === $time) {
            return true;
          }else {
            return false;
          }
        }


        /**
         * 通報理由のバリデーション
         * @param string $value  [description]
         */
        public function ReportValidation() {

          if ($reason = filter_input(INPUT_POST, 'reason_for_report')) {
            switch ($reason) {
              case 'スパム/広告':
                return true;

              case '性的コンテンツ/出会い目的':
                return true;

              case '迷惑行為':
                return true;

              case '生物に関係のない写真・投稿':
                return true;

              case 'その他':
                return true;

              default:
                return false;
            }
          }
        }


}

 ?>
