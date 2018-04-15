var socket = new WebSocket("ws://172.21.160.99:3000");

socket.onopen = function(){
	console.log("connect succeed");
}
socket.onclose = function(){
	console.log("already close");
}
socket.onmessage = function(txt){
	var msg = JSON.parse(txt.data);
	showMessage(msg.status,msg.username,msg.str);
}

function showMessage(status,username,str){
   if(status ) return ;
	$new_content = $('<li>'+str+'</li>');
	var video = $('.player-video')[0],
	    cssVideo= document.defaultView.getComputedStyle(video,null),
	    maxTop = parseInt(cssVideo.height) * 0.4;
    $new_content.css("top",Math.floor(Math.random()*maxTop) +'px');
	$new_content.appendTo($('div.player-video'))
 		.animate({right:"150%"},6000,'linear',function(){
			$(this).remove(); 
		})
	$('#msg').append($('<li>'+username+'</li><li>:&nbsp'+str+'</li><br>'));
	$('#msg').scrollTop( $('#msg')[0].scrollHeight );
}


function getCookie(){
	var reg = new RegExp("(^| )user=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)){
        return (arr[2]);
    }else{
         return null;
    }    
}
function toggleUser(username){
	if(!($('.right:visible').length == 2 && !username))  //when username =='' && 初始登录注册界面,这不toggle
		$('.right').toggle();
	console.log(username);
	if(username)		//用户名存在
		$('#header').append($('<li>'+username+'</li><img src="./img/head.png" id="head">'));
	else{
		$('#header li').remove();
		$('#head').remove();
	}
}
$(function(){
	var username = getCookie();
	toggleUser(username);
	$('#btn-sendMsg').click(function(){
		if(!username) console.log('请先登录后才能发弹幕');
		else {
			$textarea = $('input').eq(0);
			var txt = $textarea.val();
			if(txt){
				var msg = {};
				msg.status = 0;
				msg.str = txt ;
				msg.username = username;
				socket.send(JSON.stringify(msg));
				$textarea.val('');
			}
		}
	})
	$('.right').click(function(){
		var url = "http://172.21.160.99/video/logn.html?action=";
		if($(this).text()=="注册") url += "b";
		else if($(this).text()=="登陆")  url += "a";
			 else {
					document.cookie="user=";
					toggleUser(getCookie());
					return ;
			 }
		window.location.href = url;
	})
})