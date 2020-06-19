// Initializing toasts
$(".toast").toast({
    autohide: false
});

/* Set the width of the side navigation to 19rem */
function openNav() {
    $("#side-nav").css("left", "0rem");
}

/* Set the width of the side navigation to 0 */
function closeNav() {
    $("#side-nav").css("left", "-19rem");
}

$(window).click(function(e) {
    if (e.clientX > 304) {
        closeNav();
    }
});

$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

$("#create-class-nav-item").click(function(e) {
    closeNav();
});

var createClassForm = document.getElementById("create-class-form");
var createClassFormLoader = document.getElementById("create-class-form-loader");
var errorMessages = document.getElementById("error-messages");

var xhr;

if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
} else {
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

createClassForm.addEventListener("submit", function(e) {
    e.preventDefault();
    var formData = new FormData(createClassForm);
    // Holds the status of the XMLHttpRequest.
    // 0: request not initialized
    // 1: server connection established
    // 2: request received
    // 3: processing request
    // 4: request finished and response is ready
    // console.log(this);
    xhr.onreadystatechange = function() {
        errorMessages.style.display = "none";
        errorMessages.innerHTML = "";
        createClassForm.style.display = "none";
        createClassFormLoader.style.display = "block";
        if (this.readyState == 4 && this.status == 201) {
            $(".toast").css("visibility", "visible");
            $(".toast").toast("show");
            var data = JSON.parse(this.responseText);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else if (this.readyState == 4 && this.status == 422) {
            var data = JSON.parse(this.responseText);
            var errors = data.errors;
            createClassForm.style.display = "block";
            createClassFormLoader.style.display = "none";
            var ul = document.createElement("ul");
            var keys = Object.keys(errors);
            keys.forEach(key => {
                var li = document.createElement("li");
                li.innerHTML = "<li>" + errors[key][0] + "</li>";
                ul.appendChild(li);
            });
            errorMessages.appendChild(ul);
            errorMessages.style.display = "block";
        }
    };

    xhr.open(createClassForm.method, createClassForm.action);
    xhr.send(formData);
});
