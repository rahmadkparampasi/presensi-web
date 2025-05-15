function setTime() {
    var d = new Date(),
      el = document.getElementById("time");

      el.innerHTML = formatAMPM(d);

    setTimeout(setTime, 1000);
    }

    function formatAMPM(date) {
      var months = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
      var
        year = date.getFullYear(),
        day = date.getDate(),
        month = months[date.getMonth()],
        hours = date.getHours(),
        minutes = date.getMinutes(),
        seconds = date.getSeconds(),
        ampm = hours >= 12 ? 'pm' : 'am';
      hours = hours % 24;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0'+minutes : minutes;
      var strTime = day + '&nbsp' + month + '&nbsp' + year + '&nbsp' + hours + ':' + minutes + ':' + seconds;
      return strTime;
    }

    setTime();