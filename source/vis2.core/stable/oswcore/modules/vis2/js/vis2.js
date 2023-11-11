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
        z_index: 1331,
        mouse_over: 'pause',
        type: notify_type,
        template: '<div data-notify="container" class="alert alert-{0} col-6 alert-dismissible fade show" role="alert"><span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a><div class="float-right"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    });
}

const scrollToTop = function () {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

$(function () {
    const scrollButton = document.getElementById('scrollToTop');

    if (scrollButton != null) {
        scrollButton.addEventListener('click', scrollToTop);
    }
});

window.addEventListener('load', function () {
    document.querySelector('body').classList.add("loaded")
});
