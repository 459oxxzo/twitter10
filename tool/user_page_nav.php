
<div class="page_nav">
  <?php 
  //ページ数を数えて（1p30件）、ページリンクを出力
  if($amount>30){
    //30件以上ならページング発生。（総件数/30）ページ作る
    for($i=0;$i*30<$amount["amount"];$i++){
      //自分の今いるページにはリンク張らない
      if(empty($_GET["page"]) && $i==0){
        //トップページにいる場合
        echo "<span class='pagelink'>1</span>";
      }elseif(!empty($_GET["page"]) && $i+1==$_GET["page"]){
        //2ページ目以降にいる場合
        echo "<span class='pagelink'>".($i+1)."</span>";
      }else{
        //それ以外のページにはリンク
        echo "<a class='pagelink' href='".$this_page."?u_id=".$_GET["u_id"]."&&page=".($i+1)."'>".($i+1)."</a>";        
      }
    }
  }
  ?>
</div>