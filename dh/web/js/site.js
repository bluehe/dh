function my_alert(type, content, out = 0) {
    var html = '';
    html += '<div id="my_alert" class="alert-' + type + ' alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + content + '</div>';
    $('.content-wrapper').prepend(html);
    if (out) {
        setTimeout(function () {
            $('.content-wrapper #my_alert').slideUp(500);
        }, out);
    }
}

$('.website').on('click', '.clickurl', function () {
    var id = $(this).parents('.list-group-item').data('id');
    if (id) {
        $.get("/ajax/website-click", {id: id}, function (result) {});
    }
});

$('.recommend').on('click', '.clickurl', function () {
    var id = $(this).data('id');
    if (id) {
        $.get("/ajax/recommend-click", {id: id}, function (result) {});
    }
});

