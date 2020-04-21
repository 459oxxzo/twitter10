<div class="modal_on">
  <!-- ここから投稿フォーム -->
  <div id="m_form">
    <button class="modal_off">✖</button>

    <section class="modal_top">  
      <img id="r_icon" src="">

      <div id="target_tweet">  
        <span id="r_nickname"></span>
        <span id="r_user_id"></span>
        <p id="r_content"></p>
      </div>
    </section>

    <p id="r_msg"><p>

    <section class="modal_form">

      <div class="modal_left">
        <!-- <div class="rep_line"></div> -->
        <p><?php echo "@".$_SESSION["u_id"]; ?></p>
        <div>
          <img class="icon_mini" src="icon/<?php 
          if(empty($_SESSION["icon"])){
              echo "default.jpg";
          }else{echo $_SESSION["icon"];
          } ?>">
        </div> 
      </div>
      
      <div class="modal_retweet">
        <form action="api/retweet_post.php" method="post">  
          <input type="text" name="t_id" id="retweet_frm" value="">
          <button type="submit">リツイート</button>
        </form>
      </div>
      
      <div class="modal_right">
        <form action="api/tweet_post.php" method="post" enctype="multipart/form-data">
          <textarea name="content" cols="50" rows="5"></textarea><br>
          <input type="hidden" name="reply_to" id="reply_to" value="">
          <input type="file" name="img" id="m_file">
          <button type="submit">送信</button>
        </form><div id="m_preview"><img src="" ></div><p id="picdata"></p>
      </div>

    </section>
    <!-- <span> GIF </span><span> 投票 </span><span> 予約 </span> -->

  </div>
  <!-- ここまで -->
</div>
<!-- モーダルエンド -->