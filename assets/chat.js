import './styles/chat.scss';

import 'select2/dist/css/select2.min.css';

require('select2');

$("#showMembers").on('click', function (e) {
    const icon = $(this).children().eq(1).children().eq(0);
    icon.toggleClass('fa-angle-right');
    icon.toggleClass('fa-angle-down');
});

$(".select-participants").select2({
    placeholder: "Selectionner un ami",
    allowClear: true,
    width: "100%",
    style: 'width: 100%'
});

$("#addMember").on('click', function (event) {
    $("#moreInformationModal").modal('hide');
    $("#addMemberModal").modal('show');
})