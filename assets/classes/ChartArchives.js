//CLASSES
import { Dates } from './Dates.js';
import Chart from "chart.js"

//JQUERY
import $ from 'jquery';


//FOS ROUTING SYMFONY TO JS 
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


export class ChartArchives {

    constructor(eltId, month) {
        this.eltId = $(eltId);
        this.month = month;
        this.dates = new Dates();

        this.initChart();
        this.resizeChartFont();
    }

    //Initialise charts.js
    initChart() {
        this.getMonthData(this.month);
        Chart.defaults.global.defaultFontColor = "#fff";
    }
   
    //Récupère les données du mois choisi, créée un tableau de données puis lance le graphique avec ce tableau
    getMonthData(month) {
        let that = this;
        $.ajax({
            url: Routing.generate('mood.archives.month', {month: month}),
            type: "POST",
            async: true, 
            success: function(moods) {
                let date;
                let dataArray = []; // = le tableau qu'on va envoyer au graphique pour construire la courbe
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
                            moodNumber = that.checkMoodNumber(moods[i]);
                            dataArray.push(moodNumber);
                            i++
                        } else {
                            //si le mood n'a pas été fait ce jour-là, on met "non renseigné" dans le graph
                            dataArray.push(0);
                        }
                    } else {
                        dataArray.push(0);
                    }
                    
                }
    
                //Une fois le tablea dataArray rempli, je l'envoie pour initialiser le graphique
                that.startChart(dataArray);
                
                
            },
            error: function(data) {
                alert("Erreur provenant du serveur");
                console.log(data);
            }
          });
    }

    //Fonction qui va crééer le tableau de données nécéssaire au montage du graphique
    checkMoodNumber(mood) {
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

    //Lancement du graphique
    startChart(dataArray) {
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
    
        var myChart = new Chart(this.eltId, {
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
                    text: 'Variation des humeurs du mois (2020)'
                }
            }
        });
    }

    //Ajuste la taille des fonts du graphique en fonction des media queries
    resizeChartFont() {
        if(window.matchMedia('(max-width:600px)').matches) {
            Chart.defaults.global.defaultFontSize = 12;
        } else {
            Chart.defaults.global.defaultFontSize = 20;
        }
    }

}