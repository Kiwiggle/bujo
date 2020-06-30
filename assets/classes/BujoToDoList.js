import $ from 'jquery';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

import "../../public/js/slick-1.8.1/slick/slick.css"
import "../../public/js/slick-1.8.1/slick/slick.js"

export class BujoToDoList {

    constructor(elementId, newEntryElementId) {
        this.elementId = elementId;
        this.newEntryElement = newEntryElementId;

        this.previous = 1;

        this.date = new Date();
        this.day = this.date.getDate();
        this.month = this.date.getMonth() + 1;
        this.year = this.date.getFullYear();
        this.formattedDate;

        this.sliderInit();
    }

    sliderInit() {
        $(this.elementId).slick({
            arrows:false,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            draggable:false,
            swipe:false,
        });
    }

    formatDate() {
    
        this.day = this.day - this.previous;
    
        if (this.day == 0) {
            this.date = new Date(this.year, this.month - 1, 0);
            this.day = this.date.getDate();
            this.month = this.date.getMonth() + 1;
        }
    
        return this.formattedDate = this.year + "-" + this.month + "-" + this.day;
    }

    newEntryInTodaysToDoList() {
        let that = this;
        $.ajax({
            url: Routing.generate('todolist.new'),
            type: "POST",
            async: true, 
            success: function(data) {
                $(that.newEntryElement).append(data);
            },
            error: function(data) {
                alert('Erreur côté serveur');
                console.log(data.responseText);
            }
        });
    }

    loadPreviousToDoList() {
        let that = this;
        
        this.formattedDate = this.formatDate();

        $.ajax({
            url: Routing.generate('todolist.previous'),
            type: "POST",
            data: {
                "date" : that.formattedDate
            },
            async: true, 
            success: function(data) {
                $(that.elementId).slick('slickAdd', data, 0, 1);
                let currentSlide = $(that.elementId).slick('slickCurrentSlide');
                $(that.elementId).slick('slickGoTo', currentSlide, true);
            },
            error: function(data) {
                alert('Erreur côté serveur');
                console.log(data.responseText);
            }
        });
    }

    moveToNextToDoList() {
        let currentSlide = $(this.elementId).slick('slickCurrentSlide');
        $(this.elementId).slick('slickGoTo', currentSlide + 1, true);
    }


}