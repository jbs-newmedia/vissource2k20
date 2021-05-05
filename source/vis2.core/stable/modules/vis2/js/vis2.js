function checkVIS2Logout() {
	if (typeof session_timeout !== 'undefined') {
		if (Math.floor(jQuery.now() / 1000) >= session_timeout) {
			window.location.href = session_logout;
		}
	}
}

$(function () {
	setInterval(function () {
		checkVIS2Logout();
	}, 1000);
});

function vis2_notify(notify_msg, notify_type) {
	if (!notify_type) {
		notify_type = 'info';
	}
	$.notify({
		message: notify_msg
	}, {
		offset: {
			x: 40,
			y: 60
		},
		placement: {
			from: 'top',
			align: 'center'
		},
		delay: 2500,
		mouse_over: 'pause',
		type: notify_type
	});
}
