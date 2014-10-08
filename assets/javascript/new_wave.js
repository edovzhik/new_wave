$(function () {
    $('.contact').click(function () {
        var contact_id = $(this).find('.contact_id').text();
        $.post('./',
            {
                action: "ajax_get_messages",
                contact_id: contact_id
            },
            function (data, status) {
                if(data != false) {
                    alert(data);
                }
            });
    })
})
