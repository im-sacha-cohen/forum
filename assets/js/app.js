$(function() {
    $('.btn-leave-comment').on('click', function() {
        $(this).toggleClass('hidden');
        $('.form-add-comment').toggleClass('hidden');
    });

    $('.btn-cancel').on('click', function(e) {
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