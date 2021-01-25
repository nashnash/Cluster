import './styles/chat.scss';

$("#showMembers").on('click', function (e) {
    const icon = $(this).children().eq(1).children().eq(0);
    icon.toggleClass('fa-angle-right');
    icon.toggleClass('fa-angle-down');
});
