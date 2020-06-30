import $ from 'jquery';

//Cette classe travaille sur tout ce qui concerne les dates : jours, mois, années, calculs...
export class Dates {

    //Traduit les options des <select> produits par Symfony
    translateSelect(id) {
        $(id).find('option').remove();
        let options = '<option value="1">Janvier</option>'
        options += '<option value="2">Fevrier</option>'
        options += '<option value="3">Mars</option>'
        options += '<option value="4">Avril</option>'
        options += '<option value="5">Mai</option>'
        options += '<option value="6">Juin</option>'
        options += '<option value="7">Juillet</option>'
        options += '<option value="8">Août</option>'
        options += '<option value="9">Septembre</option>'
        options += '<option value="10">Octobre</option>'
        options += '<option value="11">Novembre</option>'
        options += '<option value="12">Décembre</option>'
        $(id).append(options);
      }

    //Retourne le numéro correspondant au mois en paramètre
    checkMonth(monthToCheck) {
        let month;
    
        switch(monthToCheck) {
            case "january":
                month = "01";
                break
            case "february":
                month = "02";
                break
            case "march":
                month = "03";
                break
            case "april":
                month = "04";
                break
            case "may":
                month = "05";
                break
            case "june":
                month = "06";
                break
            case "july":
                month = "07";
                break
            case "august":
                month = "08";
                break
            case "september":
                month = "09";
                break
            case "october":
                month = "10";
                break
            case "november":
                month = "11";
                break
            case "december":
                month = "12";
                break
        }
    
        return month;
    }
}