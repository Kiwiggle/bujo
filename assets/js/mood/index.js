import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import select2 from '../../../node_modules/select2/dist/js/select2.js'
import "../../../node_modules/select2/dist/css/select2.min.css"

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


$(document).ready(function() {

    $('#mood div select').select2();

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
                console.log(data.responseText);
            }
          });
    });

    
    $('.form').click(function() {
        $('#chart').hide();
        $('.mood-search').show();
    });
});