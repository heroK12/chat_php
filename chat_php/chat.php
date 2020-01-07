<?php

//チャット機能をクラス化する
  class Chat{
    //クラス内のみで扱えるようにする。
    private $_db;
    
    //クラスをインスタンス化した瞬間に実行される
    public function __construct() {
      $this->_createToken();
  
      //phpとDBを接続している　失敗だとエラーを投げるようにしている
      try {
        $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      } catch (\PDOException $e) {
        echo $e->getMessage();
        exit;
      }
    }

    //トークンが無ければ作るようにする。(クッキーに保存されていなければという解釈)
    private function _createToken() {
      if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
      }
    }

    //チャットログをすべて取得するように変更
    public function getAll() {
      $stmt = $this->_db->query("select * from chatlog order by comeban desc");
      return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //postの処理
    public function post(){
      //Tokenの不正対策
      $this->_validateToken();

      //もし名前がセットされていれば$name変数に名前を入れる
      //うまく動かないのでjqueryの方で処理
      // if(isset($_POST["name"]) === "" || isset($_POST["comment"]) === ""){
      //   $err_msg = "名前、もしくはコメントが入力されていません";
      //   exit;
      // }
      return $this->_create();
    }

    //Tokenチェック
    private function _validateToken() {
      if (
        !isset($_SESSION['token']) ||
        !isset($_POST['token']) ||
        $_SESSION['token'] !== $_POST['token']
      ) {
        throw new \Exception('invalid token!');
      }
    }

    //DBにセットする
    private function _create(){
      // if (!isset($_POST['name']) || !isset($_POST['comment'])) {
      //   throw new \Exception('名前、もしくはコメントが入力されていません');
      // }
      $sql = "insert into chatlog (name,log,created) values (:name,:comment,:date)";
      $stmt = $this->_db->prepare($sql);
      $stmt->bindParam(':name',$_POST['name']);
      $stmt->bindParam(':comment',$_POST['comment']);
      $stmt->bindParam(':date',date("Y-m-d H:i:s"));
      $stmt->execute();

      
      
      return [
        'comeban' => $this->_db->lastInsertId()
      ];
    }


  }