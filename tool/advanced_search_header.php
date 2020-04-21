<h1>高度な検索  </h1>

<?php if(!empty($_GET)):?>
		<h3><?php 
			//var_dump($_GET);
			$str="";
			if(!empty($_GET["kw"])){
				$str.="キーワード:{$_GET["kw"]} ";
				if(!empty($_GET["andor1"] && $cntw!=null && $cntw>1)){
					if($_GET["andor1"]=="and_kw" ){
						$str.="(AND検索) ";
					}elseif($_GET["andor1"]=="or_kw"){
						$str.="(OR検索) ";
					}
				}
			}
			if(!empty($_GET["u_id"])){
				$str.="ユーザー:{$_GET["u_id"]} ";
			}
			if(!empty($_GET["since"])){
				$str.="{$_GET["since"]}から ";
			}
			if(!empty($_GET["until"])){
				$str.="{$_GET["until"]}まで ";
			}
			if(!empty($_GET["tag"]) || !empty($_GET["htm"])){
				$str.="(";
				if(!empty($_GET["tag"])){
					$str.="タグ";
				}
				if(!empty($_GET["tag"]) && !empty($_GET["htm"])){
					$str.=",";
				}
				if(!empty($_GET["htm"])){
					$str.="URL";
				}
				$str.="含む)";
			}
			echo $str." の検索結果"; ?></h3>
<?php else: ?>
     <h3><?php echo "詳細検索"; ?></h3>
<?php endif; ?>
<section>
 
<form action="advanced_search.php" method="get">
	<p>本文に<input type="text" name="kw">を含む
		<input type="radio" name="andor1" value="and_kw" checked="checked">AND検索
		<input type="radio" name="andor1" value="or_kw">OR検索</p>
	<p>名前に<input type="text" name="u_id"><span>を含む(OR検索)</span>
	<p><input type="checkbox" id="tag" name="tag" value="tag">
    <label for="tag">タグを含む投稿で絞り込む</label>
    <input type="checkbox" id="htm" name="htm" value="htm">
    <label for="htm">URLを含む投稿で絞り込む</label></p>
    <p><input type="date" id="since" name="since">から</p>
    <p><input type="date" id="until" name="until">まで</p>
	<input type="submit" value="検索">
</form>

</section>