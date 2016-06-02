/**
 * Created by corentin on 31/05/16.
 */

$(document).ready(function () {

    $_scores = $('.score > input');

    $_scores.on('keyup', function () {
        $_sibling = $('input[name="' + $(this).attr('data-name') + '"]');
        if ($_sibling.val() == $(this).val() && $.trim($(this).val()) != '') {
            $(this).parent().parent().next().removeClass('hidden');
        }
        else {
            $(this).parent().parent().next().addClass('hidden');
        }
    });
});