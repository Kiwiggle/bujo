//CLASSES
import { BujoCalendar } from '../../classes/BujoCalendar.js';

//CSS imports
import "@fullcalendar/core/main.css";
import "@fullcalendar/daygrid/main.css";
import "../calendar/calendar.css"; 

document.addEventListener('DOMContentLoaded', function() {

  //Prévient l'user que l'orientation paysage est conseillée pour l'utilisation de l'app sur smartphone
  checkOrientation();
  window.addEventListener('orientationchange', function() {
    setTimeout(checkOrientation, 200);
  }); 

  //Prépare et lance full calendar
  initCalendar();

});

function checkOrientation() {
  if (window.innerHeight > window.innerWidth) {
      alert('Pour une meilleure utilisation de l\'agenda, passez votre appareil en mode paysage')
  }
}

function initCalendar() {
  let calendarEl = document.getElementById('calendar');
  let eventsUrl = calendarEl.dataset.eventsUrl;
  let agenda = new BujoCalendar(calendarEl, eventsUrl);
}


