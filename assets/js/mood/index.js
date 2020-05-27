import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import select2 from '../../../node_modules/select2/dist/js/select2.js'
import "../../../node_modules/select2/dist/css/select2.min.css"

$(document).ready(function() {
    $('#mood div select').select2();
});