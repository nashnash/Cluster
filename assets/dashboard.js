$("#new_post_content").on('change paste keyup', function (event) {
    const value = $(this).val();
    if (value.length > 100) {
        $(this).val(value.slice(0, 100 - value.length));
    }
    $("#length").text($(this).val().length)
})