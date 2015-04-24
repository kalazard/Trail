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

//Afficher le modal de confirmation de suppression de catégorie
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
}

//Modification d'une catégorie
function modifCategorie(idCategorie)
{
    $('#modalModifCategoryForm').children().remove();
    $('#modalModifCategoryForm').remove();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_category_modification'),
        data: {"idCategorie" : idCategorie},
        cache: false,
        success: function(data){
            $("body").append(data);
            $("#modalModifCategoryForm").modal('show');
        }
    });
}

//Envoi du formulaire de modification
function envoiFormModif()
{
    var data = $('#modifierCategory').serialize();
    
    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_category_modification'),
        cache: false,
        data: data,
        success: function(){
            document.location.href=Routing.generate('site_trail_gallery')
        }
    });
}

$( "body" ).on( "click", "#addPicture", function()
{
    $("#modalAddPictureForm").children().remove();
    $("#modalAddPictureForm").remove();

    $.ajax({
        type: "POST",
        url: Routing.generate('site_trail_picture_showAdd'),
        cache: false,
        success: function(data){
            $('body').append(data);
            $("#modalAddPictureForm").modal('show');
        }
    });
});