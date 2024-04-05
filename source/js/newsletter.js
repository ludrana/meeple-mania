function newsletterClicked() {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    var email = document.getElementById("newsletter1").value;
    $.ajax({
        type: "POST",
        url: "source/php/newsletter.php",
        data: { email: email },
        dataType: "json",
        error: function (xhr, status, error) {
            toastText.innerText = xhr.responseJSON.message;
            toastBody.classList.add('fail');
            toast.show();
            setTimeout(function () {
                toastBody.classList.remove('fail');
                toast.hide();
            }, 1500);
        },
        success: function (data) {
            var msg = data.message;
            toastText.innerText = msg;
            toastBody.classList.add('success');
            toast.show();
            setTimeout(function () {
                toastBody.classList.remove('success');
                toast.hide();
            }, 1500);
        }
    });
};

document.getElementById('closeToastButton').addEventListener('click', function () {
    var toast = document.getElementById('toast');
    if (toast.classList.contains('success')) {
        toast.classList.remove('success');
    }
    else {
        toast.classList.remove('fail');
    }
});