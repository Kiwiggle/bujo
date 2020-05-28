import $ from 'jquery';
import Chart from "../../../node_modules/chart.js/dist/Chart.js"

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

$(document).ready(function() {

    $('.month').click(function(event) {
        let month = checkMonth(event.currentTarget.id);
        getMonthData(month);
    })
    
});

function checkMonth(monthToCheck) {
    let month;

    switch(event.currentTarget.id) {
        case "january":
            month = "01";
            break
        case "february":
            month = "02";
            break
        case "march":
            month = "03";
            break
        case "april":
            month = "04";
            break
        case "may":
            month = "05";
            break
        case "june":
            month = "06";
            break
        case "july":
            month = "07";
            break
        case "august":
            month = "08";
            break
        case "september":
            month = "09";
            break
        case "october":
            month = "10";
            break
        case "november":
            month = "11";
            break
        case "december":
            month = "12";
            break
    }

    return month;
}

function getMonthData(month) {
    $.ajax({
        url: Routing.generate('mood.archives.month', {month: month}),
        type: "POST",
        async: true, 
        success: function(data) {
            let date;
            let dataArray = [];
            let moodNumber;
            let i = 0;

            for (let j = 1; j <= 31; j++) {
                if (data[i] != undefined) {
                    date = new Date(data[i].date.date); 
                    date = date.getDate();
                    if (j == date) {
                        moodNumber = checkMoodNumber(data[i]);
                        dataArray.push(moodNumber);
                        i++
                    } else {
                        dataArray.push(0);
                    }
                } else {
                    dataArray.push(0);
                }
                
            }
            initChart(dataArray);
            
            
        },
        error: function(data) {
            console.log(data.responseText);
        }
      });
}

function checkMoodNumber(mood) {
    let moodNumber;
    switch(mood.feeling) {
        case "Horrible":
            moodNumber = 1;
            break;
        case "Pas terrible":
            moodNumber = 2;
            break;
        case "Moyen":
            moodNumber = 3;
            break;
        case "Bien":
            moodNumber = 4;
            break;
    }
    return moodNumber;
}

function initChart(dataArray) {
    let months = []
    for (let i = 1 ; i <= 31 ; i++) {
        months.push(i);
    }

    var yLabels = {
        0 : "Non renseigné",
        1 : 'Horrible',
        2 : 'Pas terrible',
        3 : 'Moyen',
        4 : 'Bien',
    }
    
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                data: dataArray,
                borderWidth: 2,
                backgroundColor: "#ff6397"
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return yLabels[value];
                        }
                    }
                }]
            },
            title: {
                display: true,
                text: 'Variation des émotions du mois (2020)'
            }
        }
    });
}



