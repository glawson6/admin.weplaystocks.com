$('.nav-tab').each(function() {
    var path = location.pathname;
    if ($(this).parent().attr('href') !== path.substring(path.lastIndexOf('/') + 1)) return;
    $(this).css({
        'background-color': '#ffffff',
        'border-bottom': 'none',
        'height': '35px'
    });
});

var addMessageListeners = function() {
    $('.instructor-message').click(function() {
        var msg = $(this);
        if (msg.hasClass('expanded')) {
            msg.removeClass('expanded');
        } else {
            msg.addClass('expanded');
        }
    });
};
addMessageListeners();
