function addToCart(id) {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/addtocart.php",
        data: { gameid: id },
        dataType: "json",
        error: function (xhr, status, error) {
            console.log(xhr.responseJSON.message);
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

$(document).ready(function () {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    const queryString = window.location.search;
    sessionStorage.setItem("query", JSON.stringify(queryString));
    const urlParams = new URLSearchParams(queryString);
    $("form#ratingForm").submit(function (e) {
        e.preventDefault(); // prevent the default click action from being performed
        if ($("#ratingForm :radio:checked").length == 0) {
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: "source/php/rating.php",
                data: { ratingval: $('input:radio[name=rating]:checked').val(), game: urlParams.get('game') },
                dataType: "json",
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON.message);
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
                    const savedFormValues = JSON.parse(sessionStorage.getItem("query"));
                    window.location.href = savedFormValues;
                }
            });
        }
    });
});
