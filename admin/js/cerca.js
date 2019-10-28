$(document).ready(function(ev) {
	$("#keysearch").css('margin-top',10);
	$("#keysearch").keyup(function (e) {
		if (e.keyCode == 13) {
			//alert($(this).attr("data-dest"));
			var key = $("#keysearch").val();
			var strkey = "";
			if(key.length>0){
				strkey = "&key="+key;
			}
			window.location.href = $(this).attr("data-dest")+strkey;
		}
	});    
});
