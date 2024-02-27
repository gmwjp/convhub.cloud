$(function(){
	$("body").on("click",".submit",function(e){
		e.stopPropagation();
		var c = $(this).data("confirm");
		var form = $(this).closest("form");

		var flg = true;
		if(typeof c != "undefined"){
			if(!confirm(c)){
				flg = false;
			}
		}

		if(flg){
			var action = $(this).data("action");
			if(typeof action != "undifined"){
				form.attr("action",action);
			}
			var method = $(this).data("method");
			if(typeof method != "undifined"){
				form.attr("method",method);
			}
			form.submit();
		}
	});
	$("body").on("click",".href",function(e){
		e.stopPropagation();
		var c = $(this).data("confirm");
		var atten_id = $(this).data("atten-id");
		var flg = true;
		if(typeof c != "undefined"){
			if(!confirm(c)){
				flg = false;
			}
		}

		if(flg){
			var action = $(this).data("action");
			var target = $(this).data("target");
			if(typeof action != "undifined"){
				if(typeof atten_id != "undefined"){
					$.ajax({
						type: 'POST',
						url: "/attens/onread/"+$(this).data("atten-id"),
						async:false 
					});
				}
				if(target == null){
					window.location.href = action;
				} else {
					window.open().location.href = action;
				}
			}
		}
	});
	$("body").on("click",".noaction", function (e) {
		e.stopPropagation();
	});

	$(".btn-search").on("click",function(){
		$("#search").toggle();
	});
});
function info(val){
	notif({
		type: "info",
		msg: val,
		position: "center",
		multiline:true
	});
}
function success(val){
	notif({
		type: "success",
		msg: val,
		position: "center",
		autohide:false,
		multiline:true
	});
}
function error(val){
	notif({
		type: "error",
		msg: val,
		position: "center",
		multiline:true
	});
}
