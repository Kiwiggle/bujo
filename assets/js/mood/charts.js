import $ from 'jquery';
import Chart from "../../../node_modules/chart.js/dist/Chart.js"

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

$(document).ready(function() {

    $('.month').click(function(event) {
        $('.mood-search').hide();
        $('#chart').attr("style", "display:flex;");

        let month = checkMonth(event.currentTarget.id);

        getMonthData(month);

        Chart.defaults.global.defaultFontSize = 16;
        Chart.defaults.global.defaultFontColor = "#fff";
    })
});

function getMonthData(month) {
    $.ajax({
        url: Routing.generate('mood.archives.month', {month: month}),
        type: "POST",
        async: true, 
        success: function(moods) {
            let date;
            let dataArray = [];
            let moodNumber;
            let i = 0;

            /* 
                Je créée un tableau de données qui remplira le graphique
                j représente les jours du mois
                i représente le numéro du mood à représenter sur le graphique    
            */
            for (let j = 1; j <= 31; j++) {
                if (moods[i] != undefined) {
                    //On récupère la date du mood s'il existe
                    date = new Date(moods[i].date.date); 
                    date = date.getDate();

                    //si la date du mood == la date sur laquelle on est (j)
                    if (j == date) {
                        moodNumber = checkMoodNumber(moods[i]);
                        dataArray.push(moodNumber);
                        i++
                    } else {
                        dataArray.push(0);
                    }
                } else {
                    dataArray.push(0);
                }
                
            }

            //Une fois le tablea dataArray rempli, je l'envoie pour initialiser le graphique
            initChart(dataArray);
            
            
        },
        error: function(data) {
            console.log(data.responseText);
        }
      });
}

//Initialisation du Graphique
function initChart(dataArray) {
    let months = []
    for (let i = 1 ; i <= 31 ; i++) {
        months.push(i);
    }

    //Labels des ordonnées
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
                backgroundColor: '#ff639706',
                borderColor: '#008e0c',
                pointBorderColor: "#008e0c"
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "#ffffffa5",
                        display: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return yLabels[value];
                        },
                        fontFamily: '"Prata", serif',
                        padding: 10
                    },
                    gridLines: {
                        color: "#ffffffa5",
                        zeroLineColor: "#ffffffa5"
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

//Renvoie le numéro du mois
function checkMonth(monthToCheck) {
    let month;

    switch(monthToCheck) {
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

//Renvoie le numéro du mood récupéré pour qu'il soit traité par le graphique
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
