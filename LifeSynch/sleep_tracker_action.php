<?php
include('dbConnect.php');

function saveSleepAndWakeTime(user) {
    var sleepTime = document.getElementById('sleep-time').value;
    var wakeTime = document.getElementById('wake-time').value;

    if (sleepTime && wakeTime) {
        var userId = user;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sleep_tracker_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
        };

        xhr.send('user_id=' + userId + '&sleep_time=' + sleepTime + '&wake_time=' + wakeTime);
    } else {
        alert("Please enter both sleep time and wake time.");
    }
}



