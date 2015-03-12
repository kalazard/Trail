$( "#ajouterEvent" ).on( "click", "#btnAnnuler", function()
{
    $('#ajouterEvent').children().remove();
    $('#ajouterEvent').remove();
    $('#overlay').remove();
});