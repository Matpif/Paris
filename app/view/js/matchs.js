/**
 * Created by corentin on 31/05/16.
 */

$(document).ready(function () {

    $_scores = $('.score > input[data-flag="1"]');

    $_scores.on('keyup', function () {
        $_sibling = $('input[name="' + $(this).attr('data-name') + '"]');
        if ($_sibling.val() == $(this).val() && $.trim($(this).val()) != '') {
            $(this).parent().parent().next().removeClass('hidden');
        }
        else {
            $(this).parent().parent().next().addClass('hidden');
        }
    });

    $('form').submit(function() {

        var method = $(this).attr('method');
        var action = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            method: method,
            url: action,
            data: data
        }).success(function(data) {

        }).error(function(message) {

        }).complete(function() {

        });
    });
});