<!-- ページナビ：次のページに移動（自動ページ挿入はあきらめる） -->
<?php
session_start();

  require_once("tool/config.php");


?>

<div class="page_nav">
  <?php 
  //ページ数を数えて（1p50件）、ページリンクを出力
  if($amount>50){
    //50件以上ならページング発生
    //とりあえずトップページ
    // echo "<a class='pagelink' href='timeline.php'>1</a>";

    //総件数/50ページ作る
    for($i=0;$i*50<$amount["amount"];$i++){
      //自分の今いるページにはリンク張らない
      if(empty($_GET["page"]) && $i==0){
        //トップページにいる
        echo "<span class='pagelink'>1</span>";
      }elseif(!empty($_GET["page"]) && $i+1==$_GET["page"]){
        //2ページ目以降にいる
        echo "<span class='pagelink'>".($i+1)."</span>";
      }else{
        //それ以外のページにはリンク
        echo "<a class='pagelink' href='timeline.php?page=".($i+1)."'>".($i+1)."</a>";        
      }
    }
  }
  ?>
</div>