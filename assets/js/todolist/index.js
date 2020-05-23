import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'


const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

