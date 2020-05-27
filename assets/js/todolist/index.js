import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import "../../../public/js/slick-1.8.1/slick/slick.css"
import "../../../public/js/slick-1.8.1/slick/slick.js"

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

$('#add-entry').click(function() {
    $.ajax({
        url: Routing.generate('todolist.new'),
        type: "POST",
        async: true, 
        success: function(data) {
            $('#today-list .list-design').append(data);
            $('#to_do_list_name').attr('value', 'Écrire ici...')
        },
        error: function(data) {
            console.log(data.responseText);
        }
      });
});

$(document).ready(function(){
    sliderInit();

    let previous = 0;
    let next = 0;

    $('#previous').click(function() {
        previous++;
        let date = new Date();
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();

        day = day - previous;
        date = year + "-" + month + "-" + day;

        $.ajax({
            url: Routing.generate('todolist.previous'),
            type: "POST",
            data: {
                "date" : date
            },
            async: true, 
            success: function(data) {
                $('#slick-holder').slick('slickAdd', data, 0, 1);
                let currentSlide = $('#slick-holder').slick('slickCurrentSlide');
                $('#slick-holder').slick('slickGoTo', currentSlide, true);
            },
            error: function(data) {
                console.log(data.responseText);
            }
            });
    });

  $('#next').click(function() {
        let currentSlide = $('#slick-holder').slick('slickCurrentSlide');
        $('#slick-holder').slick('slickGoTo', currentSlide + 1, true);
    });
  });


  function sliderInit() {
    $('#slick-holder').slick({
        arrows:false,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        draggable:false,
        swipe:false,
    });
}



