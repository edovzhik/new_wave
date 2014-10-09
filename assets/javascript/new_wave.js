$(function () {

    markAllWithUnread();

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

    function markContactAsSelected(contact) {
        $('.selected').removeClass('selected');
        if (contact.length > 0) {
            contact.addClass('selected');
        }
    }

    function markAllWithUnread() {
        $.post('./',
            {
                action: "ajax_get_unread"
            },
            function (data, status) {
                if (data != false) {
                    $('.contacts_list .has_unread').removeClass('has_unread');
                    data = $.parseJSON(data);
                    data.forEach(function (id) {
                        $('.contacts_list .contact_id:contains(' + id + ')').parent().addClass('has_unread');
                    });
                } else {
                    alert('fail');
                }
            });
    }

    $('.contact').click(function () {
        var contact_id = $(this).find('.contact_id').text();
        var contact_name = $(this).find('.contact_name').text();
        var contact_title = $(this).find('.contact_title').text();
        showTopPlank(contact_id, contact_name, contact_title);
        $('.section_one button').text('Refresh');
        $('.main').append('<ul class="message_list"></ul>');
        $.post('./',
            {
                action: "ajax_get_messages",
                contact_id: contact_id
            },
            function (data, status) {
                if (data != false) {
                    data = $.parseJSON(data);
                    data.forEach(printMessage);
                    $('.message_list').animate({ scrollTop: $('.message_list .message').length * 48 }, 'slow');
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
                        $('.message_details').append('<div class="from"><span class="label">From</span><span class="value">' + from + '</span></div>');
                        $('.message_details').append('<div class="to"><span class="label">To</span><span class="value">' + to + '</span></div>');
                        $('.message_details').append('<div class="timestamp">' + timestamp + '</div>');
                        $('.main').append('<div class="body"><p>' + body + '</p></div>');
                        $('.main .body').css('height', ('calc(100% - ' + ($('.top_plank').height() + 2) + 'px)'));
                        $.post('./',
                            {
                                action: "ajax_mark_as_read",
                                message_id: id
                            });
                        markAllWithUnread();
                    });
                } else {
                    $('.message_list').append('<div class="no_messages">There are no messages related to this person.</div>');
                }
            });
        var current_contact = $(this);
        $('.section_one button').off().click(function () {
            current_contact.click();
        });
        markContactAsSelected(current_contact);
        markAllWithUnread();
    });

    $('.contacts_list button').click(function (event) {
            var current_contact, contact_id, contact_name, contact_title, is_departmental;
            var previous_contact = $('.contacts_list .contact.selected');
            if ($(this).parent('.department_name').length == 0) {
                current_contact = $(this).parent();
                contact_id = current_contact.find('.contact_id').text();
                contact_name = current_contact.find('.contact_name').text();
                contact_title = current_contact.find('.contact_title').text();
                showTopPlank(contact_id, contact_name, contact_title);
                $('.section_one button').off().click(function () {
                    current_contact.click();
                });
                is_departmental = 0;
            } else {
                current_contact = $(this).parent().parent();
                contact_id = current_contact.find('.department_id').text();
                contact_name = current_contact.find('.department_name').text();
                contact_title = '';
                showTopPlank(contact_id, contact_name, contact_title);
                $('.section_one button').off().click(function () {
                        if (previous_contact.length > 0) {
                            previous_contact.click();
                        } else {
                            markContactAsSelected($('none'));
                            var full_name = $('.username').text();
                            $('.main').empty().append('<div class="welcome"><h1>Welcome, ' + full_name.slice(0, full_name.indexOf(" ")) + '!</h1></div>');
                        }
                    }
                );
                is_departmental = 1;
            }
            $('.section_one button').text('Cancel');
            var message_edit = $('<div class="message_edit"></div>');
            message_edit.append('<div class="subject_edit"><label>Subject:</label><input type="text"/></div>');
            message_edit.append('<div class="body_edit"><label>Message:</label><textarea/></div>');
            message_edit.append('<div class="send_button"><button type="button">Send</button></div>');
            message_edit.appendTo('.main');
            $('.send_button button').click(function () {
                var subject = $('.subject_edit input').val();
                var body = $('.body_edit textarea').val();
                $('.input_error').remove();
                if (subject.length >= 255 || subject.length < 1) {
                    $('.subject_edit').prepend('<div class="input_error">Subject must be from 1 to 255 characters long.</div>');
                    return false;
                }
                if (body.length >= 65000 || body.length < 1) {
                    $('.body_edit').prepend('<div class="input_error">Message must be from 1 to 65000 characters long.</div>');
                    return false;
                }
                $('.send_button button').attr('class', 'pending').text('Sending...').off();
                $.post('./', {
                        action: 'ajax_send_message',
                        receiver_id: contact_id,
                        subject: subject,
                        body: body,
                        is_departmental: is_departmental
                    },
                    function (data, status) {
                        if (data != false) {
                            $('.send_button button').attr('class', 'success').text('Success!');
                        } else {
                            $('.send_button button').attr('class', 'failed').text('Failed');
                        }
                        setTimeout(function () {
                            $('.section_one button').click();
                        }, 2000);
                    }
                );
            });
            markContactAsSelected(current_contact);
            markAllWithUnread();
            event.stopImmediatePropagation();
        }
    )
    ;


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
});
