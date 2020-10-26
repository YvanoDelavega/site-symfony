$("#add-image").click(function () {
    // cf 3. Ajouter un élément dynamiquement grâce au prototype du CollectionType.mp4

    // je récupère le numéro des futurs champs que je vais créer
    // const index = $("#annonce_images div.form-group").length;
    // en javascript val renvoi forcément un string, donc si apres on fait val(index +1), on va se retrouver avec "01" au lieu de 1
    // donc on mets un + devant l'expression pour la convertir en int
    const index = +$('#widgets-counter').val();
    console.log(index);
    // je récupere le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);
    // console.log(index);
    //console.log(tmpl);
    // j'injecte ce code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // je gere le btn supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    })
}


function updateCounter() {
    const count = +$('#annonce_images div.form-group').length;
    $('#widgets-counter').val(count);
}

// appelé au chargement de la page pour intitialiser les btns supprimer
updateCounter();
handleDeleteButtons();