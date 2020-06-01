import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import Editor from '../../../node_modules/@editorjs/editorjs/dist/editor.js';
const Header = require('@editorjs/header');
const List = require('@editorjs/list');

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import { setInterval } from 'core-js';
Routing.setRoutingData(routes);

//id est défini dans le <script> du template note.edit

if (id !== null) {
    //Je lance une requête pour récupérer la note
    let request = $.ajax({
        url: Routing.generate('note.get', {id: id}),
        type: "GET",
        async: true, 
        success: function(info) {

            //J'init editor.js en récupérant les données récupérées
            const editor = initEditor('note-edit', info);

            //Sauvegarde de la note modifiée
            $('#save').click(function(info) {
                info.preventDefault();
                saveEditor(null, editor);
                setTimeout(function() {
                    window.location.replace(Routing.generate('note.index'));
                }, 1000);
            })

            //Sauvegarde automatique toutes les minutes
            setInterval(function() {
                saveEditor(null, editor);
            }, 60000)

        }
    });

} else {
    
    const editor = initEditor('note_content');

    $('.btn').click(function(info) {
        info.preventDefault();
        saveEditor('new', editor, '#note_title');
        setTimeout(function() {
            window.location.replace(Routing.generate('note.index'));
        }, 1000);
    })
}

function initEditor(ElementId, data = null) {
    return new Editor({
        holder: ElementId,
        tools: {
            header: {
                class: Header,
                config: {
                    placeholder: 'Titre',
                    levels: [2, 3, 4],
                    defaultLevel: 3
                }
            },
            list: {
                class: List,
                inlineToolbar: true,
            }
        },
        data: data
    });
}

function saveEditor(path = null, editor, titleElement) {
    let title;
    if(titleElement != null) {
        title = $(titleElement).val();
    } else {
        title = null;
    }

    if (path === null) {
        path = 'note.edit';
    } else {
        path = 'note.' + path;
    }

    editor.save().then((outputData) => {
        $.ajax({
            url: Routing.generate(path, {id: id}),
            type: "POST",
            dataType: "json",
            data: {
                "outputData" : outputData,
                "title": title
            },
            async: true, 
            success: function(data) {
            }
        });
    }).catch((error) => {
        console.log('Saving failed: ', error)
    });
}


