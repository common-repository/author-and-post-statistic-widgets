jQuery(document).ready(function ($) {

    $(document).delegate('.chk-custom-html', 'change', function () {
        if ($(this).is(':checked')) {
            $(this).parent('.widget-custom-wrapper').children('.widgetCustomArgs').css('display', 'block');
            $(this).parent('.title-custom-wrapper').children('.titleCustomArgs').css('display', 'block');
            $(this).parent('.body-custom-wrapper').children('.bodyCustomArgs').css('display', 'block');
        } else {
            $(this).parent('.widget-custom-wrapper').children('.widgetCustomArgs').css('display', 'none');
            $(this).parent('.title-custom-wrapper').children('.titleCustomArgs').css('display', 'none');
            $(this).parent('.body-custom-wrapper').children('.bodyCustomArgs').css('display', 'none');
        }
    });

    $(function () {
        $("#from").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            maxDate: "m",
            onClose: function (selectedDate) {
                $("#to").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#to").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            maxDate: "m",
            onClose: function (selectedDate) {
                $("#from").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
    // horizontal
    $('#apswOptionsTabs').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        tabidentify: 'apsw-tabidentify', // The tab groups identifier
    });

    // vertical
    $('#apsw-nestedtabs-widgets').easyResponsiveTabs({
        type: 'vertical',
        width: 'auto',
        fit: true,
        tabidentify: 'apsw-nestedtabs-widgets', // The tab groups identifier
    });


    $(document).delegate('.apsw-tabidentify .resp-tab-item', 'click', function () {
        var activeTabIndex = $('.resp-tabs-list.apsw-tabidentify li.resp-tab-active').index();
        $.cookie('apswOptionsActiveTabIndex', activeTabIndex, {expires: 30});
    });
    var savedIndex = $.cookie('apswOptionsActiveTabIndex') >= 0 ? $.cookie('apswOptionsActiveTabIndex') : 0;
    $('.resp-tabs-list.apsw-tabidentify li').removeClass('resp-tab-active');
    $('.resp-tabs-container.apsw-tabidentify > h2').removeClass('resp-tab-active');
    $('.resp-tabs-container.apsw-tabidentify > div').removeClass('resp-tab-content-active');
    $('.resp-tabs-container.apsw-tabidentify > div').css('display', 'none');

    $('.resp-tabs-list.apsw-tabidentify li').eq(savedIndex).addClass('resp-tab-active');
    $('.resp-tabs-container.apsw-tabidentify h2').eq(savedIndex).addClass('resp-tab-active');
    $('.resp-tabs-container.apsw-tabidentify > div').eq(savedIndex).addClass('resp-tab-content-active');
    $('.resp-tabs-container.apsw-tabidentify > div').eq(savedIndex).css('display', 'block');


    var uploaderFrame;
    $(document).delegate('#addPostDefaultThumbnail', 'click', function (e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (uploaderFrame) {
            uploaderFrame.open();
            return;
        }
        //Extend the wp.media object
        uploaderFrame = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        uploaderFrame.on('select', function () {
            attachment = uploaderFrame.state().get('selection').first().toJSON();
            if ('image' == attachment.type) {
                $('#postDefaultThumbnail').val(attachment.url);
            } else {
                $('#postDefaultThumbnail').val('');
            }

        });
        //Open the uploader dialog
        uploaderFrame.open();
    });


    $(document).delegate('#apsw-delete-all', 'change', function () {
        if ($(this).is(':checked')) {
            $('#from').attr('disabled', 'disabled');
            $('#to').attr('disabled', 'disabled');
        } else {
            $('#from').removeAttr('disabled');
            $('#to').removeAttr('disabled');
        }
    });

    $(document).delegate('#apsw-delete-stats', 'click', function () {
        if (confirm(apswOptionVars.confirmDelete)) {
            $('#apsw-loader').show();
            var deleteAll = $('#apsw-delete-all').is(':checked');
            var from = $('#from').val();
            var to = $('#to').val();
            $.ajax({
                type: 'POST',
                url: apswOptionVars.url,
                data: {
                    deleteAll: deleteAll,
                    from: from,
                    to: to,
                    action: 'deleteStatistics'
                }
            }).done(function (r) {
                try {
                    var obj = $.parseJSON(r);
                    $('#apsw-loader').hide();
                    if (obj.code == 0) {
                        $('.apsw-response-msg').css('color', '#f00');
                    } else {
                        $('.apsw-response-msg').css('color', '#2EC838');
                    }
                    $('.apsw-response-msg').show();
                    $('.apsw-response-msg').text(obj.msg);
                    setTimeout(function () {
                        $('.apsw-response-msg').val('');
                        $('.apsw-response-msg').hide();
                    }, 2000);
                } catch (e) {
                    console.log(e);
                }

            });
        }
    });

    $('th.popular_by_comments').addClass('comments column-comments');
    $('td.popular_by_comments').addClass('comments column-comments');
});