$(function() {
    // Au clic sur le bouton possédant la classe HTML btn-leave-comment
    $('.btn-leave-comment').on('click', function() {
        // On ajoute ou on enlève la classe hidden -> rôle du toggleClass
        $(this).toggleClass('hidden');
        // Pareil pour l'élément HTML possédant la classe form-add-comment
        $('.form-add-comment').toggleClass('hidden');
    });

    // Au clic sur le bouton possédant la classe HTML btn-cancel
    $('.btn-cancel').on('click', function(e) {
        // On empêche le caractère par défaut de l'élément (c'est à dire de recharger la page ici)
        e.preventDefault();
        $('.form-add-comment').toggleClass('hidden');
        $('.btn-leave-comment').toggleClass('hidden');
    });

    // On ajout le smiley séléctionné dans la zone de texte quand on clique dessus
        $('.smile').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.eye').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.laugh').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.yay').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.yummy').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.tongue').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.oops').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.sad').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });

        $('.angry').on('click', function() {
            $('.text-zone')[0]['value'] += $(this).text();
        });
    //
});