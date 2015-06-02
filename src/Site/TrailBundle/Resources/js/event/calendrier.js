//Affiche le calendrier dans la div qui a pour id 'calendar'
function afficherCalendrier(listeEvenements, isCo)
{
    var eventAffiches = [];
    var idClasseEvenement = 0;
    
    for(categorie in listeEvenements)
    {
        idClasseEvenement += 1;
        
        for(evenement in listeEvenements[categorie])
        {
            var tabEvent = new Object();
            tabEvent['class'] = idClasseEvenement;
            tabEvent['color'] = '#F79B23';
                
            for(entity in listeEvenements[categorie][evenement])
            {
                if(entity === '0') //Les événements
                {
                    tabEvent['id'] = listeEvenements[categorie][evenement][entity].id;
                    tabEvent['title'] = listeEvenements[categorie][evenement][entity].evenement.titre;
                    tabEvent['start'] = listeEvenements[categorie][evenement][entity].evenement.dateDebut.date;
                    
                    tabEvent['end'] = listeEvenements[categorie][evenement][entity].evenement.dateFin.date;
                    tabEvent['statut'] = listeEvenements[categorie][evenement][entity].evenement.status.label;
                    //tabEvent['description'] = listeEvenements[categorie][evenement][entity].evenement.description;
                }
                else if(entity === '1') //Les participations
                {
                    if(listeEvenements[categorie][evenement][entity] !== null)
                    {
                        tabEvent['etatInscriptionId'] = listeEvenements[categorie][evenement][entity].id;
                        tabEvent['etatInscription'] = listeEvenements[categorie][evenement][entity].etatinscription;

                        if(listeEvenements[categorie][evenement][entity].etatinscription === 'accepte')
                        {
                            tabEvent['color'] = '#79D118';
                        }
                        else if(listeEvenements[categorie][evenement][entity].etatinscription === 'refuse')
                        {
                            tabEvent['color'] = '#FF3737';
                        }
                        else if(listeEvenements[categorie][evenement][entity].etatinscription === 'enattente')
                        {
                            tabEvent['color'] = '#E4D623';
                        }
                        else
                        {
                            tabEvent['color'] = '#F79B23';
                        } 
                    }
                    else
                    {
                        tabEvent['etatInscriptionId'] = "";
                        tabEvent['etatInscription'] = "";
                        tabEvent['color'] = '#F79B23';
                    }  
                }  
            }
            eventAffiches.push(tabEvent);
        } 
    }
    
    $(document).ready(function()
    {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#calendar').fullCalendar(
        {
            header:
            {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            lang: 'fr',
            dayClick: function(date)
            {
                if(isCo ===  true)
                {
                    $("#modalAddEventForm").remove();
                    $('#modalModifEventForm').remove();

                    $.ajax({
                        type: "POST",
                        url: Routing.generate('site_trail_evenement_ajout'),
                        cache: false,
                        data: {"dateCliquee" : date.format()},
                        success: function(data){
                            $('body').append(data);
                            $("#modalAddEventForm").modal('show');
                        }
                    });
                }                
            },
            nextDayThreshold: "00:00:01",
            events: eventAffiches,
            eventRender: function(event, element) {
                contenu = "<p>" + event.start.format('HH:mm') + " - " + event.end.format('HH:mm') + "<br/>";
                contenu += "&#8226; <a style='color:white;cursor:pointer;font-style:italic;' onclick='afficherDetail(" + event.class + ", " + event.id +")' id='detailEvenement'>" + event.title;
                if(event.statut === 'Annulé')
                {
                    contenu += " <label class='red'>(ANNULE)</label>";
                }
                contenu += "</a><br/>";
                
                //contenu += "<label class='petitTexte'>" + event.description + "</label></p>";
                element.html(contenu);
                element.css({
                    'word-wrap': 'break-word',
                });     
            }
        });
    });
}

//Formulaire d'ajout d'événement dynamique
function updateFormAddEvent(divProgrammeLabel, divProgrammeDuree, divLieuTitre, divLieuDescription, categorieEvenement)
{
    $('#specificites').children().remove();
    
    switch (categorieEvenement)
    {
        case '1': //Entrainement
            $('#specificites').append('<div class="form-group" id="programmeLabelDiv">');
            $('#programmeLabelDiv').append(divProgrammeLabel);
            $('#specificites').append('</div>');
            $('#specificites').append('<div class="form-group" id="programmeDureeDiv">');
            $('#programmeDureeDiv').append(divProgrammeDuree);
            $('#specificites').append('</div>');                     
            $('#specificites').append('<div class="form-group" id="lieuTitreDiv">');
            $('#lieuTitreDiv').append(divLieuTitre);
            $('#specificites').append('</div>');           
            $('#specificites').append('<div class="form-group" id="lieuDescriptionDiv">');
            $('#lieuDescriptionDiv').append(divLieuDescription);
            $('#specificites').append('</div>');         
            break;
        case '2': //Entrainement personnel
            break;
        case '3': //Evenement divers
            $('#specificites').append('<div class="form-group" id="grLabelRow">');
            $('#grLabelRow').append('<div class="row" id="grLabel">');
            $('#grLabel').append('<label class="col-sm-3 control-label">Description :</label>');
            $('#grLabel').append('<div class="col-sm-9" id="grLabInp">');
            $('#grLabInp').append('<input type="text" class="form-control" name="desc" maxlength="255"/>');
            $('#grLabel').append('</div>');
            $('#grLabel').append('</div>');
            $('#specificites').append('</div>');
            break;
        case '4': //Sortie découverte
            $('#specificites').append('<div class="form-group" id="lieuTitreDiv">');
            $('#lieuTitreDiv').append(divLieuTitre);
            $('#specificites').append('</div>');           
            $('#specificites').append('<div class="form-group" id="lieuDescriptionDiv">');
            $('#lieuDescriptionDiv').append(divLieuDescription);
            $('#specificites').append('</div>');   
            break;
        case '5': //Course officielle
            $('#specificites').append('<div class="form-group" id="grLabelRow">');
            $('#grLabelRow').append('<div class="row" id="grLabel">');
            $('#grLabel').append('<label class="col-sm-3 control-label">Site URL :</label>');
            $('#grLabel').append('<div class="col-sm-9" id="grLabInp">');
            $('#grLabInp').append('<input type="url" class="form-control" name="siteUrl" maxlength="255"/>');
            $('#grLabel').append('</div>');
            $('#grLabel').append('</div>');
            $('#specificites').append('</div>');
            break;
        default:
            break;
    }    
}

//Envoi du formulaire
function envoiFormAjout()
{
    var data = $('#ajouterEvent').serialize();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_ajout'),
        cache: false,
        data: data,
        success: function(data){
            document.location.href=Routing.generate('site_trail_evenement')
        }
    });
}

//Affichage des détails d'un événement
function afficherDetail(idClasse, idObj)
{
    $('#modalsEvent').children().remove();
    $('#modalsEvent').remove();
        
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_detail'),
        cache: false,
        data: {"idClasse" : idClasse,
                "idObj" : idObj},
        success: function(data){
            $('body').append(data);
            $("#modalEventDetail").modal('show');
        }
    });
}

//Afficher le modal de confirmation de suppression d'événement
function supprEvenementConfirm()
{
    $("#modalAvertissement").modal('show');
}

//Suppression d'un événement
function suppressionEvenement(idClasse, idObj)
{
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_suppression'),
        data: {"idClasse" : idClasse,
                "idObj" : idObj},
        cache: false,
        success: function(data){
            document.location.href=Routing.generate('site_trail_evenement');
        }
    });
}

//Modification d'un événement
function modifEvenement(idClasse, idObj)
{
    $("#modalAddEventForm").remove();
    $('#modalModifEventForm').remove();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_modification'),
        data: {"idClasse" : idClasse,
                "idObj" : idObj},
        cache: false,
        success: function(data){
            $("body").append(data);
            $("#modalModifEventForm").modal('show');
        }
    });
}

//Envoi du formulaire de modification
function envoiFormModif()
{
    var data = $('#modifierEvent').serialize();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_modification'),
        cache: false,
        data: data,
        success: function(){
            document.location.href=Routing.generate('site_trail_evenement')
        }
    });
}

//Affichage du modal pour les fichiers ics
$( "body" ).on( "click", "#dlCal", function()
{
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_icsForm'),
        cache: false,
        success: function(data){
            $('body').append(data);
            $('#lienDl').children().remove();
            $("#lienDl").append("<a class='btn btn-default' id='lienDl' href='"+Routing.generate('site_trail_evenement_ics')+"/default/" + $( "#dateDebut" ).val() + "/" +  $( "#dateFin" ).val() + "'>Télécharger</a>");
            $("#modalIcs").modal('show');
        }
    });
});

//Vérification des dates
function checkDate(anneeD, moisD, jourD, heureD, minuteD, anneeF, moisF, jourF, heureF, minuteF)
{    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_checkdate'),
        data: {"anneeD" : anneeD,
                "moisD" : moisD,
                "jourD" : jourD,
                "heureD" : heureD,
                "minuteD" : minuteD,
                "anneeF" : anneeF,
                "moisF" : moisF,
                "jourF" : jourF,
                "heureF" : heureF,
                "minuteF" : minuteF},
        cache: false,
        success: function(data){ 
            //Si la date est invalide (exemple : 31 février)
            if(data === "invalide")
            {
                $('#errorDate').text("Date invalide");
                $('#errorDate').css({color:"red"});
                $('input[type="submit"]').attr('disabled','disabled');
            }
            //Si la date de début est supérieure à la date de fin
            else if(data === "debutsuperieurfin")
            {
                $('#errorDate').text("La date de début est après la date de fin");
                $('#errorDate').css({color:"red"});
                $('input[type="submit"]').attr('disabled','disabled');
            }
            //Si tout est ok
            else
            {
                $('#errorDate').text("");
                $('input[type="submit"]').removeAttr('disabled');
            }
            
        }
    });
}

//Changemenet dynamique du formulaire (recherche d'événements)
function changerFormulaire(inputType)
{
    var typeSelected = $("[name^=searchType]:checked").val();
    var text = "";
    
    $('#contenuForm').children().remove();
    
    switch(typeSelected)
    {
        case 'type':
            $('#contenuForm').html(inputType);
            break;
        case 'date':
            text = "<ul>";
            text += '<li><label>Intervalle inférieur : </label> ';
            text += '<input type="text" name="dateDebut" id="dateDebut" class="date"></li> ';
            text += '<li><label>Intervalle supérieur : </label> ';
            text += '<input type="text" name="dateFin" id="dateFin" class="date"></li> ';  
            text += "</ul>";
            $('#contenuForm').html(text);
            $( ".date" ).datepicker($.datepicker.regional[ "fr" ]);
            $( ".date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            break;
        case 'typeEtDate':
            text = " <ul>";
            text += '<li><label>Intervalle inférieur : </label> ';
            text += '<input type="text" name="dateDebut" id="dateDebut" class="date"></li> ';
            text += '<li><label>Intervalle supérieur : </label> ';
            text += '<input type="text" name="dateFin" id="dateFin" class="date"></li> ';  
            text += "</ul>";
            $('#contenuForm').html(inputType + text);
            $( ".date" ).datepicker($.datepicker.regional[ "fr" ]);
            $( ".date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            break;
    }
}

function recuperationResEvenement()
{    
    var data = $('#rechEvent').serialize();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_evenement_search'),
        cache: false,
        dataType: 'json',
        data: data,
        success: function(data){
            $('#searchRes').children().remove();
            
            nbRes = 0;
            contenuHtml = "";
            nomEvenementCategorie = ["entrainement", "entrainement personnel", "événement divers", "sortie découverte", "course officielle"]; 
            tabUnique = new Array();
            
            for(cleTypeEvenement in data)
            {
                nbRes += data[cleTypeEvenement].length;
                
                for(cleEvenement in data[cleTypeEvenement])
                {
                    for(indice in data[cleTypeEvenement][cleEvenement])
                    {
                        data[cleTypeEvenement][cleEvenement][indice]['typeEvenement'] = cleTypeEvenement;
                    }
                }
                tabUnique = tabUnique.concat(data[cleTypeEvenement]);
            }
            
            contenuHtml += "<p class='text-center'>"+nbRes+" résultats trouvés</p>"
            
            for(cleEvenement in tabUnique)
            {                
                contenuHtml += "<div class='blockRes'>";
                contenuHtml += "<label> <a style='cursor:pointer' onclick='afficherDetail(" + (parseInt(tabUnique[cleEvenement][0].typeEvenement)+1) + ", " + tabUnique[cleEvenement][0].id +")'>"+tabUnique[cleEvenement][0].evenement.titre+" par "+tabUnique[cleEvenement][0].evenement.createur.prenom+tabUnique[cleEvenement][0].evenement.createur.nom+"</a></label>";
                contenuHtml += "<br/>&nbsp;&nbsp;&nbsp;&nbsp;- "+nomEvenementCategorie[tabUnique[cleEvenement][0].typeEvenement]+"<br/>";
                contenuHtml += "&nbsp;&nbsp;&nbsp;&nbsp;- du "+tabUnique[cleEvenement][0].evenement.dateDebut.date+" au "+tabUnique[cleEvenement][0].evenement.dateFin.date;
                contenuHtml += "</div>";
            }
            
            $('#searchRes').html(contenuHtml);
        }
    });
}