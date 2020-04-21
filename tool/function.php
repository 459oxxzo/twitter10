<?php

/*
-------------関数定義----------------
*/
// ◆htmlspecialcharsの短縮化
	function h($s){
		return  htmlspecialchars($s,ENT_QUOTES);
	}

// ◆投稿が今からどれくらい前かの計算
	function diff_calc($from){
    $date = new DateTime($from, new DateTimeZone('Asia/Tokyo'));

    $dt2 = new DateTime(date("Y/m/d H:i:s"));
    $dt2->setTimeZone( new DateTimeZone('Asia/Tokyo'));
  
    $diff = $date->diff($dt2);
    
    //修正：３日以上前なら日付表示、一年以上前なら年も
    if($diff->y != 0){
      //$past=$diff->y."年前";
      $past = date('Y年n月j日',strtotime($from));
    //}elseif($diff->m != 0){
      //$past=$diff->m."カ月前";
    }elseif($diff->d != 0 && $diff->d > 1){
      $past = date('n月j日',strtotime($from));
    }elseif($diff->d != 0){
      $past=$diff->d."日前";
    }elseif($diff->h != 0){
      $past=$diff->h."時間前";
    }elseif($diff->i != 0){
      $past=$diff->i."分前";
    }else{
      $past=$diff->s."秒前";
    }
    return $past;
  }

	function make_link($str){
  		$escapePattern = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); 
		$pattern       = '/((http|https):\/\/[0-9a-z'. $escapePattern. ']+)/i';
		$replaceStr    = '<a href="$1">$1</a>';
		$viewLink      = preg_replace($pattern, $replaceStr, $str);
		return $viewLink;
	}
	function make_tag($str){
  		//$escapePattern = preg_quote('-._~%:/?[]@!$&\'()*+,;=', '/'); 
		//$pattern       = '/((#|＃)[[ぁ-んァ-ヶーa-zA-Z0-9一-龠０-９、。'. $escapePattern. ']+)/i';
		$pattern       = '/(\[!\][[ぁ-んァ-ヶーa-zA-Z0-9一-龠０-９、。]+)/i';
		$replaceStr    = '<a href=search.php?kw=$1>$1</a>';
		$viewLink      = preg_replace($pattern, $replaceStr, $str);
		return $viewLink;
	}

?>

