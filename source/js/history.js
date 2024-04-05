function returnItem(id) {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/returnitem.php",
        data: {id: id},
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

            const list = document.getElementById(`return${id}`);
            list.remove();
        }
    });
}

document.getElementById('closeToastButton').addEventListener('click', function () {
    var toast = document.getElementById('toast');
    if (toast.classList.contains('success')) {
        toast.classList.remove('success');
    }
    else {
        toast.classList.remove('fail');
    }
});