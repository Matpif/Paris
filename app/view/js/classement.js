$(document).ready(function () {

    $('tr').on('click', function () {

        var userId = $(this).data('user-id');

        if (userId) {
            $.ajax("/Classement/userPoint?user="+userId)
                .done(function(data) {
                    $('#bodyModal').html(data);
                    $('#modalUserPoint').modal('show');
                });
        }
    });
});
