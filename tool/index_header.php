<span class="back">← </span><h1 style="display:inline-block">mypage</h1>

<!--
  ◇ユーザー情報表示
-->
  <div id="top_container">
    <h2><?php echo $ud["nickname"]." @".$ud["u_id"]."さんのマイページ"; ?></h2>
    <span class="tweet_count"><?php echo $ud["cnt"]; ?>件のツイート　</span>
    
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

      <div id="description"><?php echo nl2br(h($ud["description"])); ?></div>

      <table>
        <tr>
          <td><i class="fas fa-map-marker-alt"></i><?php echo $ud["location"]; ?></td>
          <td><i class="fas fa-link"></i>
<?php echo $ud["site_link"]; ?></td></tr>
        <tr>
          <td colspan=2><i class="far fa-calendar-alt"></i><?php echo date('Y年m月d日',strtotime($ud["since"])); ?>からここを利用しています</td></tr>	
        <tr>
          <th><?php echo $fdata["follow"]; ?>フォロー中　</th>
          <th><?php echo $fdata["follower"]; ?>フォロワー　</th></tr>
      </table>
     </div>
   </div>  