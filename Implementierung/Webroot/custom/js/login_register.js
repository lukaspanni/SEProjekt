document.addEventListener('DOMContentLoaded',function () {
    document.querySelector('#register').addEventListener('click',function(){registrationForm(true)});
    document.querySelector('#cancel_register').addEventListener('click',function(){registrationForm(false)});
});

/**
 * Toggle between registration and login form
 * @param show: show registration form | else: show login form
 */
function registrationForm(show){
    if(show){
        document.querySelector('#register_container').style.display = "block";
        document.querySelector('#login_container').style.display = "none" ;
        let error = document.querySelector("#login_error");
        if(error != null){
            error.remove();
        }
        window.history.pushState("Registrieren", "Registrieren", "register");
        document.title = "Registrieren";
    }else {
        document.querySelector('#register_container').style.display = "none";
        document.querySelector('#login_container').style.display ="block";
        let error = document.querySelector("#register_error");
        if(error != null){
            error.remove();
        }
        window.history.pushState("Login", "Login", "login");
        document.title = "Login";
    }
}

