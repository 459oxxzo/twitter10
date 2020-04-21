  <nav id="left_area">
<p id="fa-twttr"><a href="timeline.php"><i class="fab fa-twitter"></i></a></p><p>タイムライン</p>
<!-- <p><i class="fas fa-angle-down"></i></p> -->
<p><a href="advanced_search.php"><i class="fas fa-search"></i></a></p><p>高度な検索</p>
<p><a href="social_graph.php"><i class="far fa-chart-bar"></i></a></p><p>分析</p>

<!--<p><i class="fas fa-dove"></i></p> -->
<?php if(!empty($_SESSION["login"])): ?>
  <p><a href="index.php"><img class="icon_mini" src="icon/<?php
      if(empty($_SESSION["icon"])){
  	      echo "default.jpg";
      }else{echo $_SESSION["icon"];
  		} ?>"></a></p><p>マイページ</p>
  <p id="profile"><i class="far fa-address-card"></i></p><p>プロフ編集</p>
<?php endif; ?>

<p><i class="far fa-file-alt"></i></p><p>リスト（未）</p>
<p>
<?php if(!empty($_SESSION["login"])): ?>
  <a href="login.php"><i class="fas fa-sign-out-alt"></i></a></p><p>ログアウト</p>
<?php else: ?>
  <a href="login.php"><i class="fas fa-sign-in-alt"></i></a></p><p>ログイン</p>
<?php endif; ?>

<?php if(!empty($_SESSION["login"])): ?>
  <p><a href="withdraw.php"><i class="fas fa-user-slash"></i></a></p><p>退会</p>
  <p id="fa-pen"><i class="fas fa-pen-fancy"></i></p><p>ツイート</p>
  <p id="my_id" style="display:none"><?php echo $_SESSION["u_id"]; ?></p>
<?php endif; ?>

  </nav>
