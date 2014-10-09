$(function () {

    function printMessage(message) {
        var m_names = new Array("January", "February", "March",
            "April", "May", "June", "July", "August", "September",
            "October", "November", "December");
        var date = new Date(message.timestamp);
        var date_str = date.getHours() + ':' + date.getMinutes() + ':' + (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds()) + ' ' + m_names[date.getMonth()] + ' ' + date.getDate() + ' ' + date.getFullYear();
        var new_message = $('<div class="message"></div>');
        $('.message_list').append(new_message);
        new_message.append('<div class="' + (message.inbox == 1 ? 'inbox' : 'outbox') + '"></div>');
        new_message.append('<div class="message_id" style="display: none;">' + message.id + '</div>');
        new_message.append('<div class="message_subject">' + message.subject + '</div>');
        new_message.append('<div class="message_body" style="display: none;">' + message.body + '</div>');
        new_message.append('<div class="message_timestamp">' + date_str + '</div>');
        new_message.append('<div class="message_unread"' + (message.is_read == 1 ? ' style="display:none;"' : '') + '>NEW</div>');
        if (message.is_departmental == 1) {
            new_message.addClass('departmental');
        }
    }

    function showTopPlank(contact_id, contact_name, contact_title) {
        $('.main').empty().append('<div class="top_plank"></div>');
        $('.top_plank').append('<div class="section_one"></div>');
        $('.top_plank').append('<div class="section_two"></div>');
        $('.section_two').append('<div class="contact"></div>');
        var contact_panel = $('.section_two').find('.contact');
        contact_panel.append('<div class="contact_id" style="display: none;">' + contact_id + '</div>');
        contact_panel.append('<div class="contact_name">' + contact_name + '</div>');
        contact_panel.append('<div class="contact_title">' + contact_title + '</div>');
        $('.section_one').append('<button type="button"></button>');
    }

    $('.contact').click(function () {
        var contact_id = $(this).find('.contact_id').text();
        var contact_name = $(this).find('.contact_name').text();
        var contact_title = $(this).find('.contact_title').text();
        showTopPlank(contact_id, contact_name, contact_title);
        $('.section_one button').text('Refresh');
        $.post('./',
            {
                action: "ajax_get_messages",
                contact_id: contact_id
            },
            function (data, status) {
                if (data != false) {
                    $('.main').append('<ul class="message_list"></ul>');
                    data = $.parseJSON(data);
                    data.forEach(printMessage);
                    $('.message_list').animate({ scrollTop: $('.message_list').height() }, 'slow');
                    $('.message_list .message').click(function () {
                        var is_inbox = $(this).find('.inbox').length > 0;
                        var is_departmental = $(this).hasClass('departmental');
                        var id = $(this).find('.message_id').text();
                        var to, from;
                        if (is_departmental) {
                            if (is_inbox) {
                                to = $('.user_details .department').text();
                                from = $('.section_two .contact_name').text();
                            } else {
                                to = $('.contacts_list .contact_name:contains(' + $('.section_two .contact_name').text() + ')').parents('.department').find('.department_name span').text();
                                from = $('.user_details .username').text();
                            }
                        } else {
                            if (is_inbox) {
                                to = $('.user_details .username').text();
                                from = $('.section_two .contact_name').text();
                            } else {
                                to = $('.section_two .contact_name').text();
                                from = $('.user_details .username').text();
                            }
                        }
                        var timestamp = $(this).find('.message_timestamp').text();
                        var subject = $(this).find('.message_subject').text();
                        var body = $(this).find('.message_body').text();
                        $('.message_list').remove();
                        $('.main').append('<div class="message_body"></div>');
                        $('.section_one button').text('X');
                        $('.section_two').empty().append('<div class="message_details"></div>');
                        $('.message_details').append('<div class="id" style="display: none;">' + id + '</div>');
                        $('.message_details').append('<div class="subject">' + subject + '</div>');
                        $('.message_details').append('<div class="to"><span class="label">To</span><span class="value">' + to + '</span></div>');
                        $('.message_details').append('<div class="from"><span class="label">From</span><span class="value">' + from + '</span></div>');
                        $('.message_details').append('<div class="timestamp">' + timestamp + '</div>');
                        $('.main').append('<div class="body"><p>' + body + '</p></div>');
                        $('.main .body').css('height', ('calc(100% - ' + ($('.top_plank').height() + 2) + 'px)'));
                        $.post('./',
                            {
                                action: "ajax_mark_as_read",
                                message_id: id
                            });
                    });
                }
            });
        var current_contact = $(this);
        $('.section_one button').off().click(function () {
            current_contact.click();
        });
        $('.contacts_list .contact').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.contacts_list button').click(function (event) {
        var contact_id = $(this).find('.contact_id').text();
        var contact_name = $(this).find('.contact_name').text();
        var contact_title = $(this).find('.contact_title').text();
        showTopPlank(contact_id, contact_name, contact_title);
        $
        event.stopImmediatePropagation();
    });


    // Secrets... ;)
    $('#V').click(function () {
        if ($('#S').length == 0) {
            $('body').append('<div id="S" style="cursor: pointer; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 4in; height: calc(4in + 7em); padding: 1em; background: #1f1f1f; color: snow;">' +
                '<div style="width: 100%; height: 7em;">' +
                '<div style="text-align: justify; width: 100%;">' +
                '“And above all, watch with glittering eyes the whole world around you because the greatest secrets are always hidden in the most unlikely places. Those who don\'t believe in magic will never find it.” ' +
                '</div>' +
                '<div style="text-align:right; width: 100%; font-weight: bold; height: 1em; font-style: italic;">' +
                '- Roald Dahl' +
                '</div>' +
                '</div>' +
                '<canvas id="R" style="margin: 0.25in; width: 3.5in; height: 3.5in; background: #ffffff"  height="64" width="64"></canvas>' +
                '</div>');
            var loop;
            $('#S').click(function () {
                clearInterval(loop);
                this.remove();
            });
            var R = $('#R').get(0);
            N = [K = R.getContext('2d')];
            for (t = B = 127, I = K.getImageData(0, 0, q = 64, q); t--; M = Math.cos)N[t] = t / 43 & 1;
            loop = setInterval("t++;for(i=y=-1;y<1;y+=A)for(x=-1;x<1;x+=A=1/32,I.data[i+=4]=h+h)for(m=C=M(a=t/86),S=M(a+8),c=M(b=t/B),s=M(b+8),u=x*C+S,v=y*c-u*s,u=u*c+y*s,w=C-x*S,X=q+9*M(a+b),Y=q+9*M(b-a),Z=t,h=B;--h&&m<q;X+=u,Y+=v,Z+=w)for(m=1;N[X*m&B]+N[Y*m&B]+N[Z*m&B]<2&&m<q;m*=3);K.putImageData(I,0,0)", 9);
        }
    });
})
