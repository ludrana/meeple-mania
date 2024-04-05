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
            toastText.innerText = xhr.responseJSON.message;
            toastBody.classList.add('fail');
            toast.show();
            setTimeout(function () {
                toastBody.classList.remove('fail');
                toast.hide();
            }, 1500);
        },
        success: function (data) {
            var gamePrice = parseInt(data.price);
            const el = document.getElementById(`itemCount`);
            var countTotal = parseInt(el.innerText) + 1;
            el.innerHTML = countTotal;
            const element = document.getElementById(`count${id}`);
            var count = parseInt(element.innerText) + 1;
            element.innerHTML = count;
            const elTotal = document.getElementById("total");
            const elPrice = document.getElementById(`price${id}`);
            var total = parseInt(elTotal.innerText) + gamePrice;
            elPrice.innerHTML = `${gamePrice * count}`;
            elTotal.innerHTML = `${total}`;
        }
    });
}

function removeFromCart(id) {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/removefromcart.php",
        data: { gameid: id },
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
            if (parseInt(data.quantity) === 0) {
                const element = document.getElementById(`el${id}`);
                element.remove();
            }
            else {
                const element = document.getElementById(`count${id}`);
                element.innerHTML = parseInt(data.quantity);
                const elPrice = document.getElementById(`price${id}`);
                elPrice.innerHTML = `${parseInt(data.price) * parseInt(data.quantity)}`;
            }

            const elTotal = document.getElementById("total");
            var total = parseInt(elTotal.innerText) - parseInt(data.price);
            elTotal.innerHTML = `${total}`;

            if (document.getElementById('ul_items').getElementsByTagName('li').length == 1) {
                const container = document.getElementById("cartContainer")
                const list = document.getElementById("list");
                list.remove();
                var emptyDiv = document.createElement('div');
                emptyDiv.classList.add("col-lg-8");
                emptyDiv.classList.add("mx-auto");
                emptyDiv.innerHTML = `<p class="lead m-4">Корзина пуста</p>`;
                container.appendChild(emptyDiv);
            }
            else {
                const itemCount = document.getElementById("itemCount");
                var count = parseInt(itemCount.innerText);
                itemCount.innerHTML = `${count - 1}`;
            }
        }
    });
}

function removeAll(id) {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/removeall.php",
        data: { gameid: id },
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
            const element = document.getElementById(`el${id}`);
            element.remove();

            const elTotal = document.getElementById("total");
            var total = parseInt(elTotal.innerText) - parseInt(data.price) * parseInt(data.quantity);
            elTotal.innerHTML = `${total}`;

            if (document.getElementById('ul_items').getElementsByTagName('li').length == 1) {
                const container = document.getElementById("cartContainer")
                const list = document.getElementById("list");
                list.remove();
                var emptyDiv = document.createElement('div');
                emptyDiv.classList.add("col-lg-8");
                emptyDiv.classList.add("mx-auto");
                emptyDiv.innerHTML = `<p class="lead m-4">Корзина пуста</p>`;
                container.appendChild(emptyDiv);
            }
            else {
                const itemCount = document.getElementById("itemCount");
                var count = parseInt(itemCount.innerText);
                itemCount.innerHTML = `${count - parseInt(data.quantity)}`;
            }
        }
    });
}

function buy() {
    var toastBody = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastBody);
    var toastText = document.getElementById("toastText");
    $.ajax({
        type: "POST",
        url: "source/php/buy.php",
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

            const container = document.getElementById("cartContainer")
            const list = document.getElementById("list");
            list.remove();
            var emptyDiv = document.createElement('div');
            emptyDiv.classList.add("col-lg-8");
            emptyDiv.classList.add("mx-auto");
            emptyDiv.innerHTML = `<p class="lead m-4">Корзина пуста</p>`;
            container.appendChild(emptyDiv);
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