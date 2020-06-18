import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import frLocale from '@fullcalendar/core/locales/fr';

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

import $ from 'jquery';

import "@fullcalendar/core/main.css";
import "@fullcalendar/daygrid/main.css";


import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'


//Init FullCalendar
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var eventsUrl = calendarEl.dataset.eventsUrl;

  var calendar = new Calendar(calendarEl, {
    plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
    editable: true,
    locales: frLocale,
    locale: 'fr',
    timeZone: "UTC",
    eventSources: [
        {
            url: eventsUrl,
            method: "POST",
            extraParams: {
                filters: JSON.stringify({})
            },
            failure: () => {
               alert("Erreur lors de la construction du calendrier");
            },
        },
    ],

    //En cas de drag&drop d'un événement
    eventDrop: function(info) {

      if (!confirm("Souhaitez-vous modifier la date de l'événement ?")) {
        info.revert();
      } else {
        let start = info.event.start.toISOString();
        let end = info.event.end.toISOString();
        let url = info.event.url;
        let id = url.match(/(\d+)/); //Pour récupérer l'id de l'event

        $.ajax({
          url: Routing.generate('dropUpdate', {id: id[0]}),
          type: "POST",
          dataType: "json",
          data: {
            "start": start,
            "end": end,
            "id": id[0]
          },
          async: true, 
          success: function(data) {
            alert(data.success);
          },
          error: function(data) {
            alert(data.error);
          }
        });

        } 
      },

    //Clic sur un événement
    eventClick: function(info) {
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
                  $('.popup').show();
                },
                error: function(data) {
                  console.log("erreur 2ème ajax : " + data.responseText);
                }
              });
            })
          },
          error: function(data) {
            console.log(data.responseText);
          }
        });
      }
    });

  calendar.render();
});

//Clic sur le bouton "créer un événement"
$("#create-event").click(function() {
  $.ajax({
    url: Routing.generate('booking_new'),
    type: "POST",
    async: true, 
    success: function(data) {
      $('.form-container').remove();
      $('.popup').append(data);
      $('.popup').show();
    },
    error: function(data) {
      console.log("erreur 2ème ajax : " + data.responseText);
    }
  });

  $('.popup').show;
})
