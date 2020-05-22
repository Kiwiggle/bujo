import "./index.css"; // this will create a form.css file reachable to 'encore_entry_link_tags'


window.addEventListener("load", function() {
    var now = new Date();
    var utcString = now.toISOString().substring(0,19);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var localDatetime = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) +
                      utcString.substring(16,19);
    var datetimeField = document.getElementById("booking_beginAt");
    datetimeField.value = localDatetime;

    hour = now.getHours() + 1;
    localDatetime = year + "-" +
                    (month < 10 ? "0" + month.toString() : month) + "-" +
                    (day < 10 ? "0" + day.toString() : day) + "T" +
                    (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                    (minute < 10 ? "0" + minute.toString() : minute) +
                    utcString.substring(16,19);
    let secondDateTimeField = document.getElementById("booking_endAt");
    secondDateTimeField.value = localDatetime;
});


