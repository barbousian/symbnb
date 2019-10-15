// loads the jquery package from node_modules
var $ = require('jquery');

$('#add-image').click(function() {
    // je veux savoir quel va être le No de mon futur champs 
    // le + sert à transformer la valeur est un nombre
    const index = +$('#widgets-counter').val();
    // je récupère le prototype qui a été créé par symfony = code html nécessaire à la création du nouveau formulaire
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);
    // j'injecte ce code dans la div
    $('#annonce_images').append(tmpl);
    $('#widgets-counter').val(index + 1);
    // j'active la gestion du bouton supprimer
    handleDeleteButtons();
});
// gestion du bouton supprimer d'une ligne d'image
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count= +$('#annonce_images div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButtons();
