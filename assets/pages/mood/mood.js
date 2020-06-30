
//JQUERY
import $ from 'jquery';

//CSS
import "./mood.css"; 

//SELECT2
import select2 from 'select2'
import "../../../node_modules/select2/dist/css/select2.min.css"

//FOS ROUTING 
const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


$(document).ready(function() {

    //Lance Select2 pour le formulaire
    $('#mood div select').select2();

    //Permet la modification du Mood d'aujourd'hui, relance un formulaire - AJAX
    $('.edit-button a').click(function(info) {
        info.preventDefault();
        let url = info.target.attributes[0].value;
        let id = url.match(/(\d+)/); //Pour récupérer l'id de l'event

        $.ajax({
            url: Routing.generate('mood.edit', {id: id[0]}),
            type: "POST",
            async: true, 
            success: function(data) {
                $('.data').remove();
                $('#today-mood').append(data);
                $('#mood div select').select2();
            },
            error: function(data) {
                alert('Erreur côté serveur')
                console.log(data.responseText);
            }
        });
    });

    
});


