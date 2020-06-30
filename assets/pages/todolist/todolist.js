//JQUERY
import $ from 'jquery';

//CSS
import "./todolist.css"; 

//CLASSES
import { BujoToDoList } from '../../classes/BujoToDoList.js';


$(document).ready(function(){
    //Initialisation du moteur de la To Do List
    let toDoList = new BujoToDoList('#slick-holder', '#today-list .list-design');

    //Charge la To Do List du jour précédent
    $('#previous').click(function() {
        toDoList.loadPreviousToDoList();
    });

    //Bouge le slider vers la To Do List suivante déjà existante
    $('#next').click(function() {
        toDoList.moveToNextToDoList();
    });

    //Ajoute une entrée à la To Do List d'aujourd'hui
    $('#add-entry').click(function() {
        toDoList.newEntryInTodaysToDoList();
    });
});


  




