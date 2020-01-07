<?php
  //クッキーとやり取りする場合に使用する
  session_start();

 
  require_once(__DIR__ . '/config.php');
  require_once(__DIR__ . '/function.php');
  require_once(__DIR__ . '/chat.php');

  //チャットログを取得
  $chatapp = new Chat();
  $chatlogs = $chatapp->getAll();

  


?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Chatサイト</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
  <div class="err_pop">
  
  </div>
  <form  action="" method="post">
    <div class="container" id="container_id">
      <div class="nickname">
        <p>名前:
          <input type="text" id="name" name="name" value="">
        </p>
      </div>
      <div class="come">
        <p>コメント:
          <textarea type="text" id="comment" name="comment" cols="30" rows="5" value=""></textarea>
        </p>
      </div>
      <input id="btn" type="submit" name="send" value="コメント送信" disabled>
    </div>
  </form>
  <div class="success_pop" id="success_pop">
    <?php foreach($chatlogs as $chatlog) :?>
      <p id="id_<?=h($chatlog->comeban);?>"class="chatlog" data-id="<?=h($chatlog->comeban);?>"><?="コメント番号".h($chatlog->comeban)."　　　"."名前：".h($chatlog->name)."　　　".h($chatlog->created);?> 
      <?= "<br>";?>
      <?="コメント"."　:　".h($chatlog->log);?>
    <?php endforeach; ?>
  </div>
  <div>
  </div>
  <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    $(function(){
      $('.err_pop').fadeOut(2000);
      //nameとcommentが変更されたときに実行する
      $("#name").on("keydown keyup keypress change", function() {
        if ($("#name").val().length == 0 || $("#comment").val().length == 0) {
          $("#btn").prop("disabled", true);
        }else{
          $("#btn").prop("disabled", false);
        }
      });

      $("#comment").on("keydown keyup keypress change", function() {
        if ($("#name").val().length == 0 || $("#comment").val().length == 0) {
          $("#btn").prop("disabled", true);
        }else{
          $("#btn").prop("disabled", false);
        }
      });
    });

    
    


    $('#btn').on('click',function() {
      var name = $('#name').val();
      var comment = $('#comment').val();
      $.post('_ajax.php', {
      name: name,
      comment: comment,
      token: $('#token').val()
    },function(res) {
      //コメント番号を取得　:実験:DBから更新前に取得できるか確認
      // console.log(res);
       });
      // 更新をするかしないか　falseでする！
      // return false;
    });
  </script>
  </body>
</html>