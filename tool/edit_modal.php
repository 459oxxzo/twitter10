<div id="edit_modal_on">
  <!-- ここから投稿フォーム -->
  <div id="edit_form">
    <button id="edit_modal_off">✖</button>
    <h3>既存ツイートの編集</h3>
    <form action="api/edit_tweet.php" method="post" enctype="multipart/form-data">
      <textarea rows="10" cols="60" id="edit_content" name="content">
      </textarea>
      <input type="text" value="" name="t_id" id="edit_t_id">
      <div id="edit_media"></div>
      <input type="file" name="img" id="edit_file">
      <button type="submit" name="submit">送信</button>
    </form>
  </div>
</div>