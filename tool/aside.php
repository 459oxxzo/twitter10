  <aside id="right_area">
    <section id="search_box">
      <form action="advanced_search.php" method="get">
          <button type="submit" id="s_btn"><i class="fas fa-search"></i></button>
          <input type="text" name="kw" id="s_txt" placeholder="キーワード検索">
          <input type="hidden" name="andor1" value="and_kw">
      </form>
    </section>
    <div id="ad">　アドスペース</div>
 
  <?php
  // ◆ モーダルウインドウ（ログイン時のみ）
    if(!empty($_SESSION["login"])){
      require("modal.php");
      require("media_modal.php");
      require("profile_modal.php");
      require("edit_modal.php");
    }
  ?>

  </aside>
