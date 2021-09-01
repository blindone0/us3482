var start, f;

function request(data, success) {
	start = new Date().getTime();
	f = success;
	$.ajax({
		type: "POST",
		url: "handler.php",
		data: data,
		success: function (res) {
			$("#time").text(new Date().getTime() - start);
			f(res);
		}
	});
}

$(function () {
	$("#new").on("click", function(){
		request({do: "new"},
			function (res) {
				window.location.reload();
			}
		);
	});

	$("#get").on("click", function(){
		request({do: "get", id: $("#id").val()},
			function (res) {
				$("#result").val(res);
			}
		);
	});

	$(document).on("click", ".file", function(){
		$(".file").removeClass("warning");
		$(this).addClass("warning");
		$("#file-name").text($(this).data("file"));
		request({do: "read", file: $(this).data("file")},
			function (res) {
				$("#code").html(res);
			}
		);
	});

	request({do: "init"},
		function (res) {
			$("#files").html(res);
			request({do: "insert"},
				function (res) {
					$("#error").text(res);
					$("#form").show();
				});
		}
	);
});