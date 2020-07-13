document.addEventListener('DOMContentLoaded', function () {
    let timeButtons = document.querySelectorAll('a.time-toggle');
    for (let btn of timeButtons) {
        btn.addEventListener('click', toggleRecording);
    }
    if (document.querySelector('.invitation') != null) {
        let invitationButtons = document.querySelectorAll('.invitation a');
        for (let btn of invitationButtons) {
            btn.addEventListener('click', handleInvitation);
        }
    }
    let downloadBtn = document.querySelector('#download');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadCSV);
    }

    //Update currentRecording time
    setInterval(() => {
        if (currentRecording != null) {
            let activeProjectElem = document.querySelector('[data-projectid="' + currentRecording.ProjectId + '"');
            let projectWorkingTime = activeProjectElem.querySelector('.total-time');
            console.log(projectWorkingTime);
            updateTotalTime(projectWorkingTime);
        }
    }, 15000);
});

/**
 * toggle time recording => start/stop
 * @param e
 */
function toggleRecording(e) {
    e.preventDefault();
    let btn = e.currentTarget;
    let projectActive = false;
    let startIndex = btn.href.indexOf('start');
    if (currentRecording != null && startIndex != -1 && currentRecording.ProjectId) {
        if (btn.dataset.projectid != currentRecording.ProjectId) {
            let confirmation = window.confirm("The current Running Project will be stopped. Please Confirm.");
            if (!confirmation) return;
            projectActive = true;
        }
    }
    sendAjax(btn.href, function () {
        if (startIndex != -1) {
            if (projectActive) {
                updateOldActive();
            }
            btn.href = "/time/stop";
            btn.querySelector("i").innerText = "pause";
            loadCurrentRecording();
        } else {
            btn.href = "/time/start/" + btn.dataset.projectid;
            btn.querySelector("i").innerText = "play_arrow";
            loadCurrentRecording();
        }
    });
}

/**
 * accept/decline project invitation
 * @param e
 */
function handleInvitation(e) {
    e.preventDefault();
    let btn = e.currentTarget;
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4) {
            if (this.status == 200) {
                btn.parentNode.parentNode.parentNode.style.display = "none";
                if (document.querySelector('.invitation-container .invitation') != null) {
                    document.querySelector('.invitation-container').style.display = "none";
                }
            } else if (this.status = 400) {
                alert("There was an Error fulfilling your request.");
            }
        }
    };
    request.open('POST', btn.href);
    request.send();
}

/**
 * set button-action to start recording after starting different project
 */
function updateOldActive() {
    let card = document.querySelector('[data-projectid="' + currentRecording.ProjectId + '"]');
    let activeBtn = card.querySelector('a.btn-floating');
    if (activeBtn != null) {
        activeBtn.href = "/time/start/" + activeBtn.dataset.projectid;
        activeBtn.querySelector("i").innerText = "play_arrow";
    }
}

/**
 * send data to url and execute callback if successful
 * @param url
 * @param callback
 */
function sendAjax(url, callback) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            callback();
        }
    };
    request.open('POST', url);
    request.send();
}

/**
 * calculate new total time and update element
 * @param projectWorkingTime
 */
function updateTotalTime(projectWorkingTime) {
    let now = new Date();
    let currentMinutes = Math.floor(((now - currentRecording.StartTime) / 1000) / 60);
    let totalMinutes = parseInt(projectWorkingTime.dataset.minutes) + currentMinutes;
    console.log(minutesToTimeString(totalMinutes));
    projectWorkingTime.innerText = minutesToTimeString(totalMinutes);

}

/**
 * Parse Time Entries into array and start download as CSV
 */
function downloadCSV() {
    let rows = Array();
    let projectName = document.querySelector("#projectName").textContent;
    let tableContent = document.querySelectorAll('tbody tr');
    for (entry of tableContent) {
        // Slightly different format: seconds are not included to be more consistent because they are also not included in the html page
        rows.push([entry.childNodes[0].textContent.replaceAll(".", "/"), entry.childNodes[1].textContent.replaceAll(".", "/"), projectName]);
    }
    console.log(rows);
    //Source: https://stackoverflow.com/a/14966131
    let csvContent = "data:text/csv;charset=utf-8,"
        + rows.map(e => e.join(",")).join("\n");
    let encodedUri = encodeURI(csvContent);
    window.open(encodedUri);
}
