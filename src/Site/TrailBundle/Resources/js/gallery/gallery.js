$( "body" ).on( "click", "#addCategorie", function()
{
    $("#modalAddCategorieForm").children().remove();
    $("#modalAddCategorieForm").remove();

    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_category_ajout'),
        cache: false,
        success: function(data){
            $('body').append(data);
            $("#modalAddCategorieForm").modal('show');
        }
    });
});

//Envoi du formulaire
function envoiFormAjout()
{
    var data = $('#ajouterCategorie').serialize();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_category_ajout'),
        cache: false,
        data: data,
        success: function(data){
            document.location.href=Routing.generate('site_trail_gallery')
        }
    });
}

/*//Afficher le modal de confirmation de suppression de catégorie
function supprCategorieConfirm()
{
    $("#modalAvertissement").modal('show');
}

//Suppression d'une catégorie
function suppressionCategorie(idCategorie)
{    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_category_suppression'),
        data: {"idCategorie" : idCategorie},
        cache: false,
        success: function(){
            document.location.href=Routing.generate('site_trail_gallery');
        }
    });
}*/