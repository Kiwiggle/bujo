import $ from 'jquery';
import "./index.css"; // this will create a calendar.css file reachable to 'encore_entry_link_tags'
import Editor from '../../../node_modules/@editorjs/editorjs/dist/editor.js';
const Header = require('@editorjs/header');
const List = require('@editorjs/list');

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
import { setInterval } from 'core-js';
Routing.setRoutingData(routes);

//id est défini dans le <script> du template

//Je lance une requête pour récupérer la note
let request = $.ajax({
    url: Routing.generate('note.get', {id: id}),
    type: "GET",
    async: true, 
    success: function(info) {

        //J'init editor.js en récupérant les données récupérées
        const editor = new Editor({
            holder: 'note-edit',
            tools: {
                header: {
                    class: Header,
                    config: {
                        placeholder: 'Enter a header',
                        levels: [2, 3, 4],
                        defaultLevel: 3
                      }
                },
                list: {
                    class: List,
                    inlineToolbar: true,
                }
            },
            data: info
        });

        //Sauvegarde de la note modifiée
        $('#save').click(function(info) {
            info.preventDefault();
            saveEditor(editor);
        })

        //Sauvegarde automatique toutes les minutes
        setInterval(function() {
            saveEditor(editor);
        }, 60000)

    }
  });

  function saveEditor(editor) {
    editor.save().then((outputData) => {
        $.ajax({
            url: Routing.generate('note.edit', {id: id}),
            type: "POST",
            dataType: "json",
            data: {
             "outputData" : outputData
            },
            async: true, 
            success: function(data) {
              console.log("Sauvegarde réussie");
            }
          });
      }).catch((error) => {
        console.log('Saving failed: ', error)
      });
  }


