"use strict";
document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("mailer-form");
    var message = document.getElementById("mailer-message");
    var spinner = document.getElementById("mailer-spinner");
    function submit(data) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState==4 && this.status==200) {
                try { var response = JSON.parse(this.responseText); }
                catch(error) { var response = [ false, error ]; } // server error
                message.textContent = response[1];
                spinner.style.display = "none";
                if (response[0]) form.remove();
                else message.classList.add('error');
                message.setAttribute("role", "alert");
            }
        };
        xhr.open('POST', '', true);
        xhr.send(data);
    }
    if (form) form.addEventListener("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        formData.set('__httprequest', 'xmlhttp');
        spinner.style.display = "inline";
        submit(formData);
    }, false);
});
