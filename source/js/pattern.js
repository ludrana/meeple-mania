function addToCart(id) {
    if (localStorage.getItem(id.toString()) === null) {
        localStorage.setItem(id.toString(), "1")
    }
    else {
        var count = parseInt(localStorage.getItem(id.toString())) + 1
        localStorage.setItem(id.toString(), count.toString())
    }
}

window.onload = function() {
    var toastTrigger = document.getElementById("trigger")
        toastTrigger.addEventListener('click', function() {
            // Show the toast
            var toast = document.getElementById('liveToast');
            toast.classList.add('show');
            setTimeout(function() {
                toast.classList.remove('show');
              }, 1500);
});

document.getElementById('closeToastButton').addEventListener('click', function() {
    var toast = document.getElementById('liveToast');
    toast.classList.remove('show');
  });
}
