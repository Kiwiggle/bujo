//JQUERY
import $ from 'jquery';

//Editor.js
import Editor from '@editorjs/editorjs/dist/editor';
const Header = require('@editorjs/header');
const List = require('@editorjs/list');
const Paragraph = require('@editorjs/paragraph');

//FOS Routing 
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

//Set Interval
import { setInterval } from 'core-js';

export class NoteEditor {

    constructor(id = null, elementId) {
        this.id = id;
        this.elementId = elementId;

        this.startEditor();
    }

    startEditor() {
        let that = this;
        //id est défini dans le <script> du template note.edit
        
        if (this.id !== null) { //Modification d'une note pré-existante
            //Je lance une requête pour récupérer la note
            let request = $.ajax({
                url: Routing.generate('note.get', {id: id}),
                type: "GET",
                async: true, 
                success: function(info) {

                    //J'init editor.js en récupérant les données récupérées
                    const editor = that.initEditor(that.elementId, info);

                    //Sauvegarde de la note modifiée
                    $('#save').click(function(info) {
                        info.preventDefault();
                        that.saveEditor(null, editor);
                        setTimeout(function() {
                            window.location.replace(Routing.generate('note.index'));
                        }, 1000);
                    })

                    //Sauvegarde automatique toutes les minutes
                    setInterval(function() {
                        that.saveEditor(null, editor);
                    }, 60000)

                }
            });

        } else if (this.id == null){ //Création d'une note
            
            const editor = this.initEditor(this.elementId);

            $('.btn').click(function(info) {
                info.preventDefault();
                that.saveEditor('new', editor, '#note_title');
                setTimeout(function() {
                    window.location.replace(Routing.generate('note.index'));
                }, 1000);
            })

        } else {
            alert('Construction de l\'éditeur de notes impossible');
        }
    }

    //Inititalise Editor.js avec plugins paragraphe, titre et liste
    initEditor(ElementId, data = null) {

        return new Editor({
            holder: ElementId,
            tools: {
                paragraph: {
                    class: Paragraph,
                    config: {
                        placeholder: "Cliquez ici pour écrire :)"
                    }
                },
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
            //récupère les données de la note déjà créée (si existantes, sinon, note vide)
            data: data
        });
    }

    //Sauvegarde la note (créée ou modifiée)
    saveEditor(path = null, editor, titleElement) {
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
            alert('La sauvegarde a échoué')
            console.log('Saving failed: ', error)
        });
    }

}