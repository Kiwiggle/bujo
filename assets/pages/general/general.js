/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './general.css';

// JQUERY
import $ from 'jquery';


//Fait apparaître le menu général de l'app

$('#close-general-menu').click(function() {
    $('#nav-general-menu').attr('style', 'left:-100%');
});

$('#open-general-menu').click(function(){
    $('#nav-general-menu').attr('style', 'left:0; position:fixed;');
});