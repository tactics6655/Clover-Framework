(function (core) {

	core.Evt.addListener(window, 'load', function () {
		core.Element.setMenuToggleClass('.dropdown', 'open');


		$("#mobile_gnb").click(function () {
			$(".mobile_gnb_area,.dummy").show().animate({ left: 0 }, 200);
		});

		$(".dummy").click(function () {
			$(".mobile_gnb_area,.dummy").hide();
		});

	});

	core.Request.addAjaxCallback('completeLogout', function (args) {
		if (args["type"] == "error") {
			alert(args["message"]);
		} else {
			core.Browser.Redirect($.core.URL.setQuery('RToken', args["RToken"]), true);
		}
	});

	core.Request.addAjaxCallback('completeLogin', function (args) {
		if (args["type"] == "error") {
			alert(args["message"]);
		} else {
			core.Browser.Redirect($.core.URL.setQuery('RToken', args["RToken"]), true);
		}
	});

})($.core);

function member_login() {
	var url = "index.php";
	var id = $('#simple_outlogin').find('input[name=mb_id]').val();
	var pw = $('#simple_outlogin').find('input[name=mb_password]').val();
	var params = core_flower.def_mid + "=member&act=procBoardLogin&user_id=" + id + "&password=" + pw;
	$.core.Request.ajax("POST", url, params, 'completeLogin', "json");
}

function member_logout() {
	var url = "index.php";
	var params = core_flower.def_mid + "=member&act=procBoardLogout";
	$.core.Request.ajax("POST", url, params, 'completeLogout', "json");
}