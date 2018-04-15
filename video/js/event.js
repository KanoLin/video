function showMsg(type,msg){
	//当vailivate have wrong or callback have wrong
	if(msg.status==1){			
		$('<div id="stage"><div id="content">'+msg.reason+
				'</div><div class="close">X</div></div>').appendTo($('body'));
		$('.close').click(close = function(){
			$(this).parent().remove();
		})
	}
	else if(msg.action == "sign in"){
		$('<div id="stage"><div id="content">'+'登录成功<br>正在跳转中'+
			'</div><div class="close">X</div></div>').appendTo($('body'));
		document.cookie = "user="+ msg.username;
		setTimeout("window.location.href = '../index.html'",1000);
	} else{
		$('<div id="stage"><div id="content">'+'注册成功<br>跳转至登录界面'+
			'</div><div class="close">X</div></div>').appendTo($('body'));
		setTimeout("$('#stage').remove()",1000);
		$('#third>span').trigger('click');
				
	}
}

function submitData(type,username,password,email,tell){
 	$.ajax({
        type:'POST',
        url:'./php/sign.php',
        data:{
            type:	type,
			username:	username,
			password:	password,
			email:  email,
			tel: 	tell
        },
        success:function(data){
			var now = eval('('+data+')');
			now.action=type;
			now.username = username;
			showMsg("callback",now);
		}
    })	

}

function turn_to_requir(){
	var argument = window.location.search;
	if(argument) {
		if(argument[8]=='a') $('#first div:nth-child(1)').trigger("click");
		else $('#first div:nth-child(2)').trigger("click");
	}
}
$(function(){

	/*********切换界面**********/	
	$('#first div:nth-child(-n+2)').bind("click",function(){
		$(this).parent().hide().next().hide();		
	})
	
	$('#first div:nth-child(1)').click(function(){
	//	$('#head-pic').animate({"marginTop":"120px"});
		$('#head-pic').css("marginTop","120px");
		$('#footer').hide();
		$('#second').show();
	})	
	
	$('#first div:nth-child(2)').click(function(){
	//	$('#head-pic').animate({"marginTop":"40px"});
		$('#head-pic').css("marginTop","40px");
		$('#footer').show();
		$('#third').show();
	})
	
	$('#first>span').click(function(){
		window.location.href = '../index.html';
	})
	
	$('#second .choice>span:nth-child(2)').click(function(){
		$('#first div:nth-child(2)').trigger('click');
		$('#second').hide();
	})
	
	$('#third>span').click(function(){
		$('#first div:nth-child(1)').trigger('click');
		$(this).parent().hide();
	})
	/*********切换界面**********/

	/*********check & post data********/
	$('#second div.but-in').click(function(){
		var str = {};
		str.status = 1;
		str.reason = '';
		var username = $(':text:visible').val();
		if(!/^[A-Za-z0-9]{8,15}$/.test(username))
				str.reason += "用户名由8-15位数字或者数字组成<br>";
		var password = $(':password:visible').val();
		if(!/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/.test(password))
				str.reason += "密码由8-16位字母和数字混合<br>";
		if(str.reason.length>0)	showMsg("validate",str);
		else 	submitData("sign in",username,password);
				
	})
	$('#third div.but-up').click(function(){
		var str = {};
		str.status = 1;
		str.reason = '';
		var username = $(':text:visible:eq(1)').val();
		if(!/^[A-Za-z0-9]{8,15}$/.test(username))
				str.reason += "用户名由8-15位数字或者数字组成<br>";
		var password1 = $(':password:visible:eq(0)').val();
		var password2 = $(':password:visible:eq(1)').val();
		if(!/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/.test(password1))
				str.reason += "密码由8-16位字母和数字混合<br>";
		if(password1 != password2)
				str.reason += "2次密码不一致<br>";
		var email = $(':text:visible:eq(0)').val();
		if(!/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(email))
				str.reason += "邮箱格式错误<br>";
		var tell = $(':text:visible:eq(2)').val();
		if(!/^1\d{10}$/.test(tell))
				str.reason += "电话格式错误<br>";
		if($(':checked').length < 1)
				str.reason += "是否确认已阅读条款<br>";
		if(str.reason.length>0)	showMsg("validate",str);
		else 	submitData("sign up",username,password1,email,tell);	
	})
	
	

	/*********check & post data********/
	turn_to_requir();
	
})