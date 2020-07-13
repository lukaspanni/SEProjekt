let user;
let currentRecording;
let reminded = false;

document.addEventListener('DOMContentLoaded', function () {
    //MOBILE-NAV
    let elems = document.querySelectorAll('.sidenav');
    let instances = M.Sidenav.init(elems, {});
    loadUser();
    loadCurrentRecording();
    setInterval(checkBreakReminder, 1000);
    setInterval(loadCurrentRecording, 10000);
});

/**
 * load current user object
 */
function loadUser() {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            user = JSON.parse(this.responseText);
        }
    };
    request.open("GET", "/user/current/json");
    request.send();
}

/**
 * load current recording, if exists
 */
function loadCurrentRecording() {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "") return;
            currentRecording = JSON.parse(this.responseText);
            if (currentRecording != null) {
                currentRecording.StartTime = new Date(currentRecording.StartTime);
            }
        }
    };
    request.open("GET", "/time/currentTimeRecording/json");
    request.send();
}

/**
 * check if Break Reminder is due
 */
function checkBreakReminder() {
    if (user != null && currentRecording != null) {
        let now = new Date();
        let minuteDiff = ((now - currentRecording.StartTime) / 1000) / 60;
        if (minuteDiff > user.BreakReminder && !reminded) {
            //send BreakReminder
            alert("You should take a break!\nYou have been working for " + Math.floor(minuteDiff) + " minutes. Your break reminder time is currently set to " + user.BreakReminder + " minutes.");
            reminded = true;
        }
    }
}

/**
 * convert a timespan in minutes into a time string (hh:mm)
 * @param minutes
 * @returns {string}
 */
function minutesToTimeString(minutes) {
    let hours = minutes / 60;
    let flooredHours = Math.floor(hours);
    minutes = Math.floor((hours - flooredHours) * 60);
    return flooredHours + "h " + minutes + "min";
}