function log(string){
    return console.log(string);
}

function togglePassword(){
    var id = document.getElementById("input_password");
    var key = document.getElementById("visibility-password");
    document.getElementById("toggle_password").classList.toggle("yellow-text");
    if(id.type === "password"){
        id.type = "text";
        key.innerHTML = "visibility_off";       
        
    } else{
        id.type = "password";
        key.innerHTML = "visibility";               
    }
}
function togglePasswordRepeat(){
    var id = document.getElementById("input-repeat_password");
    var key = document.getElementById("visibility-password-repeat");
    document.getElementById("toggle_repeat-password").classList.toggle("yellow-text");
    if(id.type === "password"){
        id.type = "text";
        key.innerHTML = "visibility_off";  
    } else{
        id.type = "password";
        key.innerHTML = "visibility"; 
    }
}

function closePopup(){
    var pop = document.getElementById("popup");
    var page = window.location.href;
    var replaced;
    pop.remove();
    if(page.indexOf("?pop") > -1){
     path = window.location.pathname;
     http = window.location.href;
     replaced =  http.split("?pop")[0];
     location.replace(replaced);
    }
     if(page.indexOf("&") > -1){
     page = window.location.href;
     replaced =  page.split("&pop")[0];
     location.replace(replaced);
    }
}

var warningTimeout = 1140000; //19min
var timoutNow = 60000; //1min
var warningTimerID,timeoutTimerID;
var logoutURL = "../includes/logout_inc.php";

function startTimer() {
    // window.setTimeout returns an Id that can be used to start and stop a timer
    warningTimerID = window.setTimeout(warningInactive, warningTimeout);
}

function warningInactive() {
    window.clearTimeout(warningTimerID);
    timeoutTimerID = window.setTimeout(IdleTimeout, timoutNow);
    //alert("1 min voor automatische logout !");
}

function resetTimer() {
    window.clearTimeout(timeoutTimerID);
    window.clearTimeout(warningTimerID);
    startTimer();
}

// Logout the user.
function IdleTimeout() {
    // document.getElementById('logout-form').submit();
    stopTimers();
    window.location = logoutURL;
    alert("je bent automatisch uitgelogd");
}

function setupTimers () {
    document.addEventListener("mousemove", resetTimer, false);
    document.addEventListener("mousedown", resetTimer, false);
    document.addEventListener("keypress", resetTimer, false);
    document.addEventListener("onscroll", resetTimer, false);
    startTimer();
}

function stopTimers(){
    document.removeEventListener("mousemove", resetTimer, false);
    document.removeEventListener("mousedown", resetTimer, false);
    document.removeEventListener("keypress", resetTimer, false);
    document.removeEventListener("onscroll", resetTimer, false);
}

