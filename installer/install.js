let waitInstall = 0;

const startInstall = function () {
	console.log("get componenets")
	getComponent();
}

const getComponent = function () {
	$.ajax({
		url: 'installer/elements.php',
		type: "post",
		data: {},
		success: function (response) {
			$('#content').html(response);
		},
		error: function (response) {

		}
	})
}

const runInstallerFail = function (dataObj) {
	for (const key in dataObj) {
		const value = dataObj[key];
		const errorKeyClass = "." + key;
		const errorClass = "err" + key;
		const errorSelectorClass = ".err" + key;
		$(errorSelectorClass).remove();

		if (value) {
			$(`<span class="error ${errorClass} text-rose-600">${"- " + value}</span>`).appendTo(errorKeyClass)
			console.log("value", errorClass, errorKeyClass, key, value)
		}
	}
}

const runInstallerSuccess = function (dataObj) {
	$(".error").remove()

	if (dataObj.id > 0) {
		getEnding();
	}
}

const runInstallerError = function () {
	console.log("error")
}


const runInstaller = function () {
	if (waitInstall == 0) {
		$('#install_start').addClass("hidden");
		$('#install_wait').removeClass("hidden");
		waitInstall = 1;
		$.ajax({
			url: "installer/components.php",
			type: "post",
			cache: false,
			data: {
				dbhost: $('#install_dbhost').val(),
				dbuser: $('#install_dbuser').val(),
				dbpass: $('#install_dbpass').val(),
				dbname: $('#install_dbname').val(),
				url: $('#install_url').val(),
				lang: $('#install_lang').val(),
				tzone: $('#install_tzone').val(),
				username: $('#install_username').val(),
				email: $('#install_email').val(),
				password: $('#install_password').val(),
				rpassword: $('#install_rpassword').val(),
				country: $('#install_country').val(),
				dob: $('#install_dob').val()
				// purchase: $('#install_purchase').val()
			},
			success: function (response) {
				const result = JSON.parse(response);
				console.log(result)

				switch (result.status) {
					case "success":
						runInstallerSuccess(result.data);
						break;
					case "fail":
						runInstallerFail(result.data)
						break;
					case "error":
						runInstallerError(result.message)
						break;
					default:
						// console.log(response)
						break;
				}

				waitInstall = 0;
				$('#install_start').removeClass("hidden");
				$('#install_wait').addClass("hidden");
			},
			error: function () {
				waitInstall = 0;
				$('#install_start').removeClass("hidden");
				$('#install_wait').addClass("hidden");
			}
		});
	}
	else {
		return false;
	}
}

const getEnding = function () {
	$.ajax({
		url: 'installer/ending.php',
		type: "post",
		data: {},
		success: function (response) {
			$('#content').html(response);
		},
		error: function (response) {

		}
	})
}

const endInstall = function () {
	window.location.reload();
}