//JQUERY
import $ from 'jquery';

//CLASSES
import { NoteEditor } from '../../classes/NoteEditor.js';

//CSS
import "./note.css"; 


$(document).ready(function() {
    let editor;
    if (document.getElementById('note-edit') !== null) { //Modif d'une note
        editor = new NoteEditor(id /* id est défini dans le <script> du template */, 'note-edit');
        console.log(id);
    } else if (document.getElementById('note_content') !== null) { //Création d'une note
        editor = new NoteEditor(null, 'note_content');
        console.log("ok");
    }
    
})





