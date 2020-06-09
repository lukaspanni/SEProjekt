document.addEventListener('DOMContentLoaded', function () {

    let add_modal = document.querySelector('#add_project_form');
    if (add_modal != null) {
        eventListenersProjectList(add_modal)
    } else {
        let projectInfo = document.querySelector('#project-information');
        if (projectInfo != null) {
            eventListenersProjectDetails(projectInfo);
        }
    }
});

/**
 * Adding event listeners for elements of project-list-page
 * @param add_modal: Modal element for add project form
 */
function eventListenersProjectList(add_modal) {
    let add_project = M.Modal.init(add_modal);
    if (add_modal.dataset.visibility == "show") add_project.open();
    document.querySelector('#add_project').addEventListener('click', function (e) {
        e.preventDefault();
        add_project.open();
    });
}

/**
 * Adding event listeners for elements of project-details-Page
 * @param projectInfo
 */
function eventListenersProjectDetails(projectInfo) {
    //Change project details form
    let changeFormContainer = document.querySelector('#project-information-edit');
    let changeForm = changeFormContainer.querySelector('form');
    let inputContainer = changeForm.querySelector('.input-fields');
    let toggleBtn = document.querySelector('#toggle-edit');
    if (toggleBtn == null) {
        return;
    }
    let edit = false;
    //toggle edit project form
    toggleBtn.addEventListener('click', function () {
            edit = toggle(edit, toggleBtn, changeFormContainer, projectInfo);
            if (edit) {
                inputContainer.innerHTML = "";
                for (let item of projectInfo.children) {
                    let input = document.createElement('input');
                    input.type = item.dataset.type;
                    input.name = item.id + "-input";
                    input.value = item.innerText;
                    input.required = true;
                    input.addEventListener('change', function () {
                        item.textContent = input.value;
                    });
                    inputContainer.appendChild(input);
                }
                let submitBtn = document.createElement('button');
                submitBtn.type = "submit";
                submitBtn.classList = "btn waves-effect waves-light";
                submitBtn.innerHTML = "OK<i class=\"material-icons right\">send</i>";
                submitBtn.addEventListener('click', (e) => {
                    submitForm(e, changeForm);
                    edit = toggle(edit, toggleBtn, changeFormContainer, projectInfo);
                });
                inputContainer.appendChild(submitBtn);
            }
        }
    );
    //Add team member form
    let addForm = document.querySelector('form#add_member');
    addForm.addEventListener('submit', (e) => {
        submitForm(e, addForm);
    });
    //Share Form
    let shareModal = document.querySelector("#share_confirmation");
    let shareForm = M.Modal.init(shareModal);
    document.querySelector("#share-btn").addEventListener("click", function (e) {
        e.preventDefault();
        let form = shareModal.querySelector("form");
        form.addEventListener("submit", (e) => {
            submitForm(e, form, (response) => {
                alert("The share is available at: " + window.location.origin + "/project/showShared/" + JSON.parse(response)); //TODO: cleaner display
                shareForm.close()
            });
        });
        shareForm.open();
    });
    drawUserTotalChart();
    drawDateTotalChart();
}

/**
 * draw chart with total working times grouped by user
 */
function drawUserTotalChart() {
    let canvas = document.querySelector("#user-total-time-chart");
    let userTimes = JSON.parse(document.querySelector("#user-total-time").innerText);
    let chart = new Chart(canvas, {
        type: "bar",
        data: {
            labels: Object.keys(userTimes),
            datasets: [{
                label: "WorkingMinutes",
                yAxisID: "min",
                backgroundColor: "rgb(135, 206, 250)",
                data: Object.values(userTimes)
            }]
        },
        options: {
            title: {
                display: true,
                text: "Team Working Minutes"
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        offsetGridLines: true
                    }
                }],
                yAxes: [{
                    id: "min",
                    type: "linear",
                    position: "left"
                }]
            }
        }
    });
}

/**
 * draw chart with total working times grouped by day
 */
function drawDateTotalChart() {
    let canvas = document.querySelector("#team-times-chart");
    let teamTimes = JSON.parse(document.querySelector("#team-time-entries").innerText);
    let dateBased = {};
    for(entry of teamTimes){
        let date = entry.StartTime.split(" ")[0];
        if(date in dateBased) {
            dateBased[date] += parseInt(entry.WorkingMinutes);
        }else{
            dateBased[date] = parseInt(entry.WorkingMinutes);
        }
    }
    console.log(dateBased);
    let chart = new Chart(canvas, {
        type: "bar",
        data: {
            labels: Object.keys(dateBased),
            datasets: [{
                label: "WorkingMinutes",
                yAxisID: "min",
                backgroundColor: "rgb(240, 128, 128)",
                data: Object.values(dateBased),
            }]
        },
        options: {
            title: {
                display: true,
                text: "Daily Working Minutes"
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        offsetGridLines: true
                    }
                }],
                yAxes: [{
                    id: "min",
                    type: "linear",
                    position: "left"
                }]
            }
        }
    });
}

