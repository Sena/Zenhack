$(function () {
    var content = $('meta[name=controller]').prop('content');
    var method = $('meta[name=method]').prop('content');
    $('.' + content + ', .' + content + '_' + method).addClass('active');

    for (let p in permission) {
        var route = permission[p].route.replace('/', '_');
        $('.' + route).removeClass('disabled');
    }
});