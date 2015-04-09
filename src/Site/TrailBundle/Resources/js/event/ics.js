//Changement de l'url quand on change la date
$( "body" ).on( "change", ".date", function()
{
    $('#lienDl').children().remove();
    $("#lienDl").append("<a class='btn btn-default' role='button' href='"+Routing.generate('site_trail_evenement_ics')+"/default/" + $( "#dateDebut" ).val() + "/" +  $( "#dateFin" ).val() + "'>Télécharger</a>");   
}); 

//Date picker pour selectionner une date quand on clique dans la case de télécharge de fichier ics
$(function()
{
    $( ".date" ).datepicker($.datepicker.regional[ "fr" ]);
});

$("#dateDebut").datepicker('setDate', new Date());
$( ".date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
$("#dateFin").datepicker('setDate', +7);

$("#lienDl").append("<a class='btn btn-default' role='button' href='"+Routing.generate('site_trail_evenement_ics')+"/default/" + $( "#dateDebut" ).val() + "/" +  $( "#dateFin" ).val() + "'>Télécharger</a>"); 