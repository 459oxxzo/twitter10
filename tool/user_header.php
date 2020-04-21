<span class="back">← </span><h1 style="display:inline-block"><?php echo "userページ"; ?></h1>

  <div id="top_container">
    <h2><?php echo $ud["nickname"]." @".$ud["u_id"]."さんのページ" ?></h2>
    <span class="tweet_count"><?php echo $ud["cnt"] ?>件のツイート　</span>
    
    <div id="top_hero"></div><!--壁紙の表示表域-->
    <div id="top_box">
      <div id="top_icon">
          <img src="icon/<?php 
          if(empty($ud["icon"])){
              echo "default.jpg";
          }else{
              echo $ud["icon"];
          } ?>">
      </div>
      <?php if($fdata["yn"]==1): ?>
        <div id="follow_btn" class="following" data-u_id='".$ud["u_id"]."'>フォロー中</div>
      <?php else: ?>
        <div id="follow_btn" class="nofollow" data-u_id="<?php echo $ud['u_id'] ?>">フォローする</div>
      <?php endif; ?>
      <div id="description"><?php echo nl2br(h($ud["description"])); ?></div>

      <table>
        <tr>
          <td><i class="fas fa-map-marker-alt"></i><?php echo $ud["location"] ?></td>
          <td><i class="fas fa-link"></i>
<?php echo $ud["site_link"] ?></td></tr>
        <tr>
          <td colspan=2><i class="far fa-calendar-alt"></i><?php echo date('Y年m月d日',strtotime($ud["since"])); ?>からここを利用しています</td></tr>	
        <tr>
          <th><?php echo $fdata["follow"]; ?>フォロー中　</th>
          <th><?php echo $fdata["follower"]; ?>フォロワー　</th></tr>
        <tr>
          <td colspan=2>
            <?php
              if($_SESSION["login"]==true){
                //<i class="far fa-bell"></i>


                //ログインユーザーのフォロワーのうち、このユーザーをフォローしている人を抽出
                $sqlf="SELECT * FROM (SELECT saki FROM follows WHERE moto='".$_SESSION["u_id"]."') sk,( SELECT moto FROM follows WHERE saki='".$_GET["u_id"]."') mt WHERE sk.saki=mt.moto";
                $rsf2=$pdo->query($sqlf);
                // $num = 1;
                $count=$rsf2->rowCount();
                if($count>0){
                  while($rowf=$rsf2->fetch(PDO::FETCH_ASSOC)){
                    echo"<a href=user.php?u_id={$rowf['saki']}>";
                    echo $rowf["saki"]."さん</a> ";
                  }
                  if($count-3>0){
                    $num=$count-3;
                    echo "他{$num}人";
                  }
                  echo "にフォローされています";
                }else{
                  echo "フォローしている人にフォロワーはいません";
                }
              }
              //echo $ud["since"] 
            ?></td></tr>  
      </table>
     </div>
   </div>  