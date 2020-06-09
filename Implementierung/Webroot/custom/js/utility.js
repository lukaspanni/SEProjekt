/**
 * Submit Form without reloading page
 * @param e: eventArguments
 * @param form
 * @param successCallback: callback in case of success
 * @param errorCallback: callback in case of failure
 */
function submitForm(e, form, successCallback, errorCallback) {
    e.preventDefault();
    let data = new FormData();
    for (input_field of form.querySelectorAll('input')) {
        data.append(input_field.name, input_field.value);
    }

    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                for (input_field of form.querySelectorAll('input')) {
                    input_field.value = "";
                }
                if (successCallback != null) {
                    successCallback(this.responseText)
                }
            } else if (this.status = 400) {
                if (errorCallback == null) {
                    alert("There was an Error fulfilling your request.");
                    return;
                }
                errorCallback(this.responseText);
            }
        }
    };
    request.open('POST', form.action);
    request.send(data);

}

/**
 * Toggle Form
 * @param toggleState: state of toggle
 * @param toggleBtn: button to start toggle
 * @param formContainer: container-Element which contains form
 * @param infoContainer: container-Element which contains alternate content
 * @returns {boolean}
 */
function toggle(toggleState, toggleBtn, formContainer, infoContainer) {
    toggleBtn.querySelector("i").innerText = toggleBtn.querySelector("i").innerText == "create" ? "clear" : "create";
    toggleVisibility(formContainer);
    toggleVisibility(infoContainer);
    return !toggleState;
}

/**
 * hide/show element
 * @param element
 */
function toggleVisibility(element) {
    element.style.display = element.style.display == "none" ? "block" : "none";
}