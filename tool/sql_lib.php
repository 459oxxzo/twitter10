<?php
//SQLライブラリ
$new_posts="";
$new_posts_count="";

if(!empty($_SESSION["login"])){
  // $uu = $_SESSION["u_id"];
  // var_dump($_SESSION);
  //ログイン状態での、全ツイートからの新着順(本人がリツイート済みか？　退会・取り消しは非表示)
    $new_posts = "SELECT * FROM (SELECT u.u_id,withdrawn,d.nickname,since, `description`,`location`,`icon`,`frends`,`followers` FROM users u,user_details d WHERE u.u_id=d.u_id) uu, (SELECT * FROM (SELECT l.canceled,l.content,l.created,l.replies,l.retweets, l.likes,l.t_id,l.reply_to,l.u_id AS u,r.u_id AS rep_to_user FROM tweets l LEFT JOIN tweets r ON l.reply_to = r.t_id) AS tt LEFT JOIN (SELECT t_id AS wt , u_id AS wu FROM re_tweets WHERE u_id ='".$_SESSION["u_id"]."') rtw ON tt.t_id = wt) AS ttt WHERE u_id = u AND canceled=0 AND withdrawn=0 ORDER BY t_id DESC LIMIT 50";
  //POPUPはAJAXに。ならば上は不要な項目が多いからちゃんと削っておく

    $login_posts_count = "SELECT count(t_id) FROM tweets,users WHERE tweets.u_id =  users.u_id AND canceled=0 AND withdrawn=0";
}else{
    //未ログイン状態での、全ツイートからの新着順(退会・取り消しは非表示)
    $new_posts = "SELECT * FROM (SELECT u.u_id,withdrawn,d.nickname,since, `description`,`location`,`icon`,`frends`,`followers` FROM users u,user_details d WHERE u.u_id=d.u_id) uu, (SELECT * FROM (SELECT l.canceled,l.content,l.created,l.replies,l.retweets, l.likes,l.t_id,l.reply_to,l.u_id AS u,r.u_id AS rep_to_user FROM tweets l LEFT JOIN tweets r ON l.reply_to = r.t_id) AS tt LEFT JOIN (SELECT t_id AS wt , u_id AS wu FROM re_tweets WHERE t_id > 0) rtw ON tt.t_id = wt) AS ttt WHERE u_id = u AND canceled=0 AND withdrawn=0 ORDER BY t_id DESC LIMIT 50";

    $new_posts_count = "SELECT count(t_id) FROM tweets,users WHERE tweets.u_id =  users.u_id AND canceled=0 AND withdrawn=0";
}


//--------------------------------------------
//SQLライブラリ
$new_posts="";
$new_posts_count="";

if(!empty($_SESSION["login"])){
  // $uu = $_SESSION["u_id"];
  // var_dump($_SESSION);
  //ログイン状態での、全ツイートからの新着順(本人がリツイート済みか？　退会・取り消しは非表示)
    $new_posts = "SELECT * FROM (SELECT u.u_id,withdrawn,d.nickname FROM users u,user_details d WHERE u.u_id=d.u_id) uu, (SELECT * FROM (SELECT l.canceled,l.content,l.created,l.replies,l.retweets, l.likes,l.t_id,l.reply_to,l.u_id AS u,r.u_id AS rep_to_user FROM tweets l LEFT JOIN tweets r ON l.reply_to = r.t_id) AS tt LEFT JOIN (SELECT t_id AS wt , u_id AS wu FROM re_tweets WHERE u_id ='".$_SESSION["u_id"]."') rtw ON tt.t_id = wt) AS ttt WHERE u_id = u AND canceled=0 AND withdrawn=0 ORDER BY t_id DESC LIMIT 50";
  //POPUPはAJAXに。ならば上は不要な項目が多いからちゃんと削っておく

    $login_posts_count = "SELECT count(t_id) FROM tweets,users WHERE tweets.u_id =  users.u_id AND canceled=0 AND withdrawn=0";
}else{
    //未ログイン状態での、全ツイートからの新着順(退会・取り消しは非表示)
    $new_posts = "SELECT * FROM (SELECT u.u_id,withdrawn,d.nickname,since, `description`,`location`,`icon`,`frends`,`followers` FROM users u,user_details d WHERE u.u_id=d.u_id) uu, (SELECT * FROM (SELECT l.canceled,l.content,l.created,l.replies,l.retweets, l.likes,l.t_id,l.reply_to,l.u_id AS u,r.u_id AS rep_to_user FROM tweets l LEFT JOIN tweets r ON l.reply_to = r.t_id) AS tt LEFT JOIN (SELECT t_id AS wt , u_id AS wu FROM re_tweets WHERE t_id > 0) rtw ON tt.t_id = wt) AS ttt WHERE u_id = u AND canceled=0 AND withdrawn=0 ORDER BY t_id DESC LIMIT 50";

    $new_posts_count = "SELECT count(t_id) FROM tweets,users WHERE tweets.u_id =  users.u_id AND canceled=0 AND withdrawn=0";
}






//表示するページ
$lim = " LIMIT 5";

//ログインした状態でのHOME：「フォロー」「ブロック」を含むタイムライン

//特定のUSERの投稿といいねを一覧するページ

//

//
//スレッドページでの
?>