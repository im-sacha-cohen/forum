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
});