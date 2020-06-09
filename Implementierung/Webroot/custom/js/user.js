document.addEventListener('DOMContentLoaded', function () {
    let userInfo = document.querySelector('#user-information');
    if (userInfo != null) {
        let formContainer = document.querySelector('#user-information-edit');
        let form = formContainer.querySelector('form');
        let toggleBtn = document.querySelector('#toggle-edit');
        let edit = false;
        toggleBtn.addEventListener('click', function () {
                edit = toggle(edit, toggleBtn, formContainer, userInfo);
                if (edit) {
                    form.innerHTML = "";
                    for (let item of userInfo.querySelectorAll('[data-type]')) {
                        let input = document.createElement('input');
                        input.type = item.dataset.type;
                        input.name = item.id + "-input";
                        input.value = item.innerText;
                        input.required = true;
                        input.addEventListener('change', function () {
                            item.textContent = input.value;
                        });
                        form.appendChild(input);
                    }
                    let submitBtn = document.createElement('button');
                    submitBtn.type = "submit";
                    submitBtn.classList = "btn waves-effect waves-light";
                    submitBtn.innerHTML = "OK<i class=\"material-icons right\">send</i>";
                    submitBtn.addEventListener('click', (e) => {
                        submitForm(e, form);
                        loadUser();
                        edit = toggle(edit, toggleBtn, formContainer, userInfo);
                    });
                    form.appendChild(submitBtn);
                }
            }
        );
    }
});
