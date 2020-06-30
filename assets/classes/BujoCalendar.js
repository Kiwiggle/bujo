//CLASSES
import { Dates } from './Dates.js';

//FULL CALENDAR
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import frLocale from '@fullcalendar/core/locales/fr';

//FOS ROUTING SYMFONY TO JS 
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

//JQUERY
import $ from 'jquery';




export class BujoCalendar {
    
    constructor(id, eventsUrl) {
        this.id = id;
        this.eventsUrl = eventsUrl; 
        this.dates = new Dates();

        this.initCalendar();
        this.createEvent();
    }

    initCalendar() {
        let that = this;
        let calendar = new Calendar(this.id, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
            editable: true,
            locales: frLocale,
            locale: 'fr',
            timeZone: "UTC",
            editable:false,
            eventSources: [
                {
                    url: that.eventsUrl,
                    method: "POST",
                    extraParams: {
                        filters: JSON.stringify({})
                    },
                    failure: () => {
                       alert("Erreur lors de la construction du calendrier");
                    },
                },
            ],
        
            //Clic sur un événement
            eventClick: function(info) {
                that.eventOnClick(info);
            }
        });

        calendar.render();
    }

    eventOnClick(info) {
        let that = this;
        info.jsEvent.preventDefault();
            let url = info.event.url;
            let id = url.match(/(\d+)/); //Pour récupérer l'id de l'event
            $.ajax({
                url: Routing.generate('booking.loadEvent', {id: id[0]}),
                type: "POST",
                async: true, 
                success: function(data) {
                    $('.event-card').remove();
                    $("#event-click-result").append(data);
                    $("#event-click-result").show('fast');
                    $("#edit-event").click(function() {
                        $.ajax({
                            url: Routing.generate('booking_edit', {id: id[0]}),
                            type: "POST",
                            async: true, 
                            success: function(data) {
                                console.log('ok');
                                $('.form-container').remove();
                                $('.popup').append(data);
                                $('.popup-background').show();
                                $('.popup').show();
            
                                that.dates.translateSelect('#booking_beginAt_date_month');
                                that.dates.translateSelect('#booking_endAt_date_month');
                                $('option[value="2020"]').attr('selected', 'selected');
            
                                that.closePopUp();
                            },
                            error: function(data) {
                                alert('Erreur côté serveur');
                                console.log("erreur 2ème ajax : " + data);
                            }
                        });
                    })
                },
                error: function(data) {
                    alert('Erreur côté serveur');
                    console.log(data.responseText);
                }
            });
    }

    createEvent() {
        let that = this;
        $("#create-event").click(function() {
            $.ajax({
            url: Routing.generate('booking_new'),
            type: "POST",
            async: true, 
            success: function(data) {
                $('.form-container').remove();
                $('.popup').append(data);
                $('.popup-background').show();
                $('.popup').show();
                that.dates.translateSelect('#booking_beginAt_date_month');
                that.dates.translateSelect('#booking_endAt_date_month');
                $('option[value="2020"]').attr('selected', 'selected');

                that.closePopUp();
            },
            error: function(data) {
                alert("Erreur côté serveur");
                console.log("erreur 2ème ajax : " + data.responseText);
            }
            });

            $('.popup').show;
        })
    }

    closePopUp() {
        $('.close-popup').click(function(event) {
            event.preventDefault();
            $('.popup').hide();
            $('.popup-background').hide();
        });
    }
}