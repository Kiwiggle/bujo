import $ from 'jquery';
import "./mood.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import select2 from 'select2'
import "../../node_modules/select2/dist/css/select2.min.css"

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);


$(document).ready(function() {

    orientation();

    window.addEventListener('orientationchange', function() {
        orientation();
    });

    $('#mood div select').select2();

    $('#archives-nav .month').click(function(info) {
        let id = info.currentTarget.id;

        $(".month").attr('style', 'background-color: #fff; color: #77d07a;');
        $("#" + id).attr('style', 'background-color: #77d07a; color: #fff');
    });

    $('#close-menu').click(function() {
        $('#archives-nav').attr('style', 'left:-100%');
    });

    $('#open-archives-menu').click(function(){
        $('#archives-nav').attr('style', 'left:0');
    });

    $('#archives-nav div').click(function() {
        $('#archives-nav').attr('style', 'left:-100%');
    });

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

function orientation() {
    if (window.innerHeight > window.innerWidth) {
        if(window.matchMedia('(max-width:950px)').matches) {
            $("#nav-buttons").attr('style', 'margin: 2% 0 2% 0;');
        }
        
    } else {
        if(window.matchMedia('(max-width:950px)').matches) {
            $('#archives-nav .month').click(function(info) {
                $("#nav-buttons").attr('style', 'margin: 15% 0 5% 0;')
            });
            $('#archives-nav .form').click(function() {
                $("#nav-buttons").attr('style', 'margin: 2% 0 2% 0;')
            })
        }
        
    }

}
