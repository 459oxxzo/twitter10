$(function () {
	console.log("ookk");
	//左のペンボタンクリック
	$("#fa-pen").on("click", function () {
		$(".modal_on").css({ 'display': 'block' });
		$("#reply_to").val("");//設定されてる値を空に
		$(".modal_right").css({ 'display': 'inline-block' });
		$(".modal_retweet").css({ 'display': 'none' });
	});

	//モーダルオフボタンクリック
	$('.modal_off').on('click', function () {
		$(".modal_on").css({ 'display': 'none' });
		$(".modal_top").css({ 'display': 'none' });
	});

	//mediaモーダルクリック
	$('.media').on('click', function () {
		$src = $(this).children("img").attr('src');
		$(".media_modal_on").css({ 'display': 'block' });
		$("#media_view").attr('src', $src);
	});
	$('.media_modal_off').on('click', function () {
		$(".media_modal_on").css({ 'display': 'none' });
	});

	//profileモーダルクリック
	$('#profile').on('click', function () {
		$("#profile_modal_on").css({ 'display': 'block' });
	});
	$('#profile_modal_off').on('click', function () {
		$("#profile_modal_on").css({ 'display': 'none' });
	});

	//editモーダルクリック
	$('.edit').on('click', function () {
		$("#edit_modal_on").css({ 'display': 'block' });
		$tid = $(this).data("t_id");
		$cnt = $("#t_" + $tid + " .content p").text();
		$("#edit_content").html($cnt);
		$('#edit_t_id').val($tid);
	});
	$('#edit_modal_off').on('click', function () {
		$("#edit_modal_on").css({ 'display': 'none' });
	});

	//返信ボタンクリック
	$(".this_reply").on("click", function () {
	console.log("OK");
		$("#reply_to").val("");//設定されてる値を空に
		$(".modal_on").css({ 'display': 'block' });
		$(".modal_right").css({ 'display': 'inline-block' });
		$(".modal_retweet").css({ 'display': 'none' });

		$tid = $(this).parent().data("t_id");
		$("#reply_to").val($tid);
		// $ttid="t"+$tid;
		$(".modal_top").css({ 'display': 'flex' });
		$cnt = nl2br($("#t_" + $tid + " .content p").text());
		$("#r_content").html($cnt);
		//この値の入れ方を完全に覚える
		$("#r_nickname").text($("#t_" + $tid + " .nickname").text());
		$("#r_user_id").text($("#t_" + $tid + " .user_id").text());
		$("#r_icon").attr('src', $("#t_" + $tid + " .icon").attr('src'));
		$('#r_msg').text("このtweetに返信する");
	});

	//リツイートボタンクリック
	$(".nyrt").on("click", function () {
		$(".modal_on").css({ 'display': 'block' });
		$(".modal_right").css({ 'display': 'none' });
		$(".modal_retweet").css({ 'display': 'inline-block' });

		$tid = $(this).parent().data("t_id");
		$('#r_msg').text("このtweetをリツイートする");
		$("#retweet_frm").val($tid);
		//親ツイートを表示
		$(".modal_top").css({ 'display': 'flex' });
		$cnt = nl2br($("#t_" + $tid + " .content p").text());
		$("#r_content").html($cnt);
		$("#r_nickname").text($("#t_" + $tid + " .nickname").text());
		$("#r_user_id").text($("#t_" + $tid + " .user_id").text());
		$("#r_icon").attr('src', $("#t_" + $tid + " .icon").attr('src'));

	});

	//フォローする
	$(".nofollow").on("click", function (){
		console.log("nofollow");
		var it = $(this);
		var uid = $(this).data("u_id");
		console.log(uid);
		$.ajax({
	      url:"api/rewrite_follow.php",
				data:{
					'saki':uid,
					'flag':1
				},
	      type:"get",
	      dataType:"json",
	    }).done(function(d){
				console.log(d);
				it.removeClass('nofollow').addClass('following');
	    }).fail(function(){
	      console.log("NG");
	    });
	});
	//フォロー解除
	$(".following").on("click", function () {
		console.log("following");
		var it = $(this);
		var uid = $(this).data("u_id");
		$.ajax({
			url: "api/rewrite_follow.php",
			data: {
				'saki': uid,
				'flag': 0
			},
			type: "get",
			dataType: "json",
		}).done(function (d) {
			console.log(d);
			it.addClass('nofollow').removeClass('following');
		}).fail(function () {
			console.log("NG");
		});
	});
//削除とアニメーション
	$(".this_cancel").on("click", function () {
		var tid = $(this).parent().data("t_id");
		var myid = $("#my_id").text();
		var tgt = $(this).parent().parent().parent().parent();
		$.ajax({
			url: "api/cancel_tweet.php",
			data: { 't_id': tid },
			type: "get",
			dataType: "json",
		}).done(function (d) {
			console.log(d);
			tgt.css("display", "none");
		}).fail(function () {
			console.log("NG");
		});
	});
});

//テキストの改行処理
function nl2br(str) {
	str = str.replace(/\r\n/g, "<br />");
	str = str.replace(/(\n|\r)/g, "<br />");
	return str;
}
//プレヴュー
$(function () {
	//情報表示エリアを作る
	//$('input[type=file]').after('<span></span>');

	// アップロードするファイルを選択
	$('#t_form_file').change(function () {
		var file = $(this).prop('files')[0];
		//ファイルの情報を表示
		$('#picdata').text('ファイル名:' + file.name + ' / 種類:' + file.type);
		console.log(file);
		//var path = $(this).attr('path');
		//$('#preview').children('img').attr('src',path);
		if (file.size > 1000000) {
			$('#preview').text("ファイルが大きすぎます");
		} else {
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function () {
				$('#preview').children('img').attr('src', reader.result);
			}
		}

	});
	$('#m_file').change(function () {
		var file = $(this).prop('files')[0];
		$('#m_picdata').text('ファイル名:' + file.name + ' / 種類:' + file.type);
		console.log(file);
		//var path = $(this).attr('path');
		//$('#preview').children('img').attr('src',path);
		if (file.size > 1000000) {
			$('#m_preview').text("ファイルが大きすぎます");
		} else {
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function () {
				$('#m_preview').children('img').attr('src', reader.result);
			}
		}
	});
});



//like
$(function () {
	$('.nyl').on('click',function(){
		
     var ltid = $(this).parent().data('t_id');
     var trgt = $(this);
     console.log(ltid);
     
     $.ajax({
       url:"api/make_like.php",
       data:{'t_id':ltid},
       type:"get",
       dataType:"json",
     }).done(function(d){
       console.log(d);
       trgt.toggleClass('nyl');
       trgt.toggleClass('arl');
       
       trgt.children('span').text(d['likes']);
     }).fail(function(){
       console.log("NG");
     });
   });

   $('.arl').on('click',function(){
     var ltid = $(this).parent().data('t_id');
     var trgt = $(this);
     
     $.ajax({
       url:"api/make_like.php",
       data:{'t_id':ltid},
       type:"get",
       dataType:"json",
     }).done(function(d){
      trgt.toggleClass('arl').toggleClass('nyl');
      trgt.children('span').text(d['likes']);
     }).fail(function(){
       console.log("NG");
     });
   });
});
   
   
   //フォロー
$(function () {   
	$('.nofollow').on('click',function(){
		var saki_id = $(this).data('u_id');
		var trgt = $(this);

		$.ajax({
			url:"api/rewrite_follow.php",
			data:{'u_id':saki_id},
			type:"get",
			dataType:"json",
		}).done(function(d){
			trgt.toggleClass('nofollow').toggleClass('following');
			$('#follow_btn').text("フォロー中");
		}).fail(function(){
			console.log("NG");
		});
	});
});      
$(function () { 
	$('.following').on('click',function(){
		var saki_id = $(this).data('u_id');
		var trgt = $(this);

		$.ajax({
			url:"api/rewrite_follow.php",
			data:{'u_id':saki_id},
			type:"get",
			dataType:"json",
		}).done(function(d){
			trgt.toggleClass('following').toggleClass('nofollow');
			$('#follow_btn').text("フォローする");
		}).fail(function(){
			console.log("NG");
		});
	}); 
});


//返信の表示。ajaxでツイートの返信を取得し挿入
$('.rv_open').on('click', function () {
	console.log("open");
	var t_id = $(this).data().t_id;
	var this_div = $(this);
	var replies_viewer = $(this).prev();

	$.ajax({
		url: 'api/get_replies.php',
		type: 'get',
		data: { 't_id': t_id },
		dataType: 'html',

	}).done(function (data) {
		$(replies_viewer).empty();//書き出す領域をクリア
		$(replies_viewer).html(data);//書き出して
		$(this_div).removeClass("rv_open").addClass("rv_close");//ボタンを変更
		$(this_div).children("span").text("閉じる(返信をクリック)");

	}).fail(function () {
		console.log("NG");
	});
});

//閉じる処理。しかしクラスを書き換えたはずなのに、rv_closeクラスの要素をクリックしても、コンソールにはcloseではなくopenが表示され、ビューワーも閉じずに再度返信の書き出しがされていた。なぜ？？
// $('.rv_close').on('click',function(){
// 	console.log("close");
// 	$(this).prev().empty();
// 	$(this).children("span").text("返信を見る");
// 	$(this).addClass("rv_open").removeClass("rv_close");
// });
//なので、ビューワーを直接クリックで閉じることにした
$('.replies_viewer').on('click', function () {
	console.log("close");
	$(this).empty();
	$(this).next().children("span").text("返信を見る");
	$(this).next().addClass("rv_open").removeClass("rv_close");
});

//プロフィールモーダルの処理
$(function () {
// 	//$('input[type=file]').after('<span></span>');
// 	//nicknameの必須化(値がなければ登録キーが無効)
	if ($("#nickname").val().length > 0) {
		$('#submit_btn').prop('disabled', false);
	}
	$("#nickname").keyup(function () {
		var num = $(this).val().length;
		// var str = $(this).val();
		console.log(num);
		if (num > 0) {
			$('#submit_btn').prop('disabled', false);
		} else {
			$('#submit_btn').prop('disabled', true);
		}
	});

	// アップロードするファイルを選択
	$('input[type=file]').change(function () {
		var file = $(this).prop('files')[0];
		// $('span').html('ファイル名:' + file.name + ' / 種類:' + file.type);
		// console.log(file);
		if (file.size > 500000) {
			$('#preview').text("ファイルが大きすぎます");
		} else {
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function () {
				$('#preview').children('img').attr('src', reader.result);
			}
		}

	});
});

