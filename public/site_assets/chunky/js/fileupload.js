$(function () {
    'use strict';


    // Initialize the jQuery File Upload widget:
    var $fileupload = $('#fileupload');
    $fileupload.fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'server/php/',
        dropZone: $('#dropzone')
    });

    // Enable iframe cross-domain access via redirect option:
    $fileupload.fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    // Load existing files:
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $fileupload.fileupload('option', 'url'),
        dataType: 'json',
        context: $fileupload[0]
    }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, null, {result: result});
        });

});