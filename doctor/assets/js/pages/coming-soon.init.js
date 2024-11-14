document.addEventListener("DOMContentLoaded", function () {
    var now = new Date().getTime();
    var endDate = new Date("May 30, 2024").getTime();
    var startDate = now;
  
    var countdownInterval = setInterval(function () {
      var currentTime = new Date().getTime();
      var timeElapsed = currentTime - startDate;
      var timeRemaining = endDate - currentTime;
  
      if (timeRemaining < 0) {
        clearInterval(countdownInterval);
        var countdownEndMessage = document.createElement("div");
        countdownEndMessage.className = "countdown-endtxt";
        countdownEndMessage.innerHTML = "The countdown has ended!";
        var countdownElement = document.getElementById("countdown");
        if (countdownElement) {
          countdownElement.innerHTML = ""; // Clear countdown
          countdownElement.appendChild(countdownEndMessage);
        }
        return;
      }
  
      var days = Math.floor(timeElapsed / (1000 * 60 * 60 * 24));
      var hours = Math.floor((timeElapsed % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((timeElapsed % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((timeElapsed % (1000 * 60)) / 1000);
  
      var countdownHtml =
        '<div class="countdownlist-item"><div class="count-title">Days</div><div class="count-num">' +
        days +
        '</div></div><div class="countdownlist-item"><div class="count-title">Hours</div><div class="count-num">' +
        hours +
        '</div></div><div class="countdownlist-item"><div class="count-title">Minutes</div><div class="count-num">' +
        minutes +
        '</div></div><div class="countdownlist-item"><div class="count-title">Seconds</div><div class="count-num">' +
        seconds +
        "</div></div>";
  
      var countdownElement = document.getElementById("countdown");
      if (countdownElement) {
        countdownElement.innerHTML = countdownHtml;
      }
    }, 1000);
  });
  