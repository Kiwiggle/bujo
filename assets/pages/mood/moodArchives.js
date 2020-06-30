//CLASSES
import { Dates } from '../../classes/Dates.js';
import { ChartArchives } from '../../classes/ChartArchives.js';

//JQUERY
import $ from 'jquery';

$(document).ready(function() {

    //Traduction des <select>
    let dates = new Dates();
    dates.translateSelect('#date_date_month');

    //Sélection par défault des <select>
    $('option[value="2020"]').attr('selected', 'selected');
    $('option[value="1"]').attr('selected', 'selected');

    //Fait apparaître la partie recherche
    $('.form-search-mood').click(function() {
        $('#chart').hide();
        $('.mood-search').show();
    });

    //Change la couleur de la div sur laquelle on clique (dans le menu)
    $('#archives-nav .month').click(function(info) {
        let id = info.currentTarget.id;

        $(".month").attr('style', 'background-color: #fff; color: #77d07a;');
        $("#" + id).attr('style', 'background-color: #77d07a; color: #fff');
    });

    //Fait disparaitre le menu des archives après un clic 
    $('#archives-nav div').click(function() {
        $('#archives-nav').attr('style', 'left:-100%');
    });

    //Ouvre le menu des archives
    $('#open-archives-menu').click(function(){
        $('#archives-nav').attr('style', 'left:0');
    });


    //Event lors du clic sur un mois
    $('.month').click(function(event) {
        //Fait disparaître la partie recherche, et apparaître la partie graph
        $('.mood-search').hide();
        $('#chart').attr("style", "display:flex;");

        //Trouve le numéro correspondant au mois cliqué
        let month = dates.checkMonth(event.currentTarget.id);

        //Relance un nouveau graphique
        let graph = new ChartArchives("#myChart", month);
        window.addEventListener('resize', function() {
            graph.resizeChartFont();
        });
    })
});


