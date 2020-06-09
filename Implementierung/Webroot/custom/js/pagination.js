document.addEventListener('DOMContentLoaded', function () {
    //enable pagination navigation
    document.querySelectorAll('li.disabled>a').forEach(function (val) {
        val.addEventListener('click', function (e) {
            e.preventDefault();
        });
    });
});