$(function () {

    // Preload images
    //$.preloadCssImages();



    // CSS tweaks
    $('#header #nav li:last').addClass('nobg');
    $('.block_head ul').each(function () {
        $('li:first', this).addClass('nobg');
    });
    $('.block form input[type=file]').addClass('file');



    // Web stats
    $('table.stats').each(function () {

        if ($(this).attr('rel')) {
            var statsType = $(this).attr('rel');
        } else {
            var statsType = 'area';
        }

	// calculate width of graph so it doesnt overflow its parent div
        //var chart_width = ($(this).parent('div').width()) - 60;
	// hack to statically set width as something is broken with div width calculation - anni
        var chart_width = $(document).width() - 340;

        if (statsType == 'line' || statsType == 'pie') {
            $(this).hide().visualize({
                type: statsType,
                // 'bar', 'area', 'pie', 'line'
                width: chart_width,
                height: '240px',
                colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c'],

                lineDots: 'double',
                interaction: true,
                multiHover: 5,
                tooltip: true,
                tooltiphtml: function (data) {
                    var html = '';
                    for (var i = 0; i < data.point.length; i++) {
                        html += '<p class="chart_tooltip"><strong>' + data.point[i].value + '</strong> ' + data.point[i].yLabels[0] + '</p>';
                    }
                    return html;
                }
            });
        } else {
            $(this).hide().visualize({
                type: statsType,
                // 'bar', 'area', 'pie', 'line'
                width: chart_width,
                height: '240px',
                colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c']
            });
        }
    });



    // Sort table
    $("table.sortable").tablesorter({
        headers: {
            0: {
                //sorter: false
            },
            5: {
                //sorter: false
            }
        },
        // Disabled on the 1st and 6th columns
        widgets: ['zebra']
    });

    $('.block table tr th.header').css('cursor', 'pointer');



    // Check / uncheck all checkboxes
    $('.check_all').click(function () {
        $(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));
    });



    // Set WYSIWYG editor
    $('.wysiwyg').wysiwyg({
        css: "css/jquery.wysiwyg.css",
        brIE: false
    });



    // Modal boxes - to all links with rel="facebox"
    $('a[rel*=facebox]').facebox()



    // Messages
    $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
    $('.block .message .close').hover(

    function () {
        $(this).addClass('hover');
    }, function () {
        $(this).removeClass('hover');
    });

    $('.block .message .close').click(function () {
        $(this).parent().fadeOut('slow', function () {
            $(this).remove();
        });
    });



    // Form select styling
    $("form select.styled").select_skin();



    // Tabs
    $(".tab_content").hide();
    $("ul.tabs li:first-child").addClass("active").show();
    $(".block").find(".tab_content:first").show();

    $("ul.tabs li").click(function () {
        $(this).parent().find('li').removeClass("active");
        $(this).addClass("active");
        $(this).parents('.block').find(".tab_content").hide();

        var activeTab = $(this).find("a").attr("href");
        $(activeTab).show();

        // refresh visualize for IE
        $(activeTab).find('.visualize').trigger('visualizeRefresh');

        return false;
    });



    // Sidebar Tabs
    $(".sidebar_content").hide();

    if (window.location.hash && window.location.hash.match('sb')) {

        $("ul.sidemenu li a[href=" + window.location.hash + "]").parent().addClass("active").show();
        $(".block .sidebar_content#" + window.location.hash).show();
    } else {

        $("ul.sidemenu li:first-child").addClass("active").show();
        $(".block .sidebar_content:first").show();
    }

    $("ul.sidemenu li").click(function () {

        var activeTab = $(this).find("a").attr("href");
        window.location.hash = activeTab;

        $(this).parent().find('li').removeClass("active");
        $(this).addClass("active");
        $(this).parents('.block').find(".sidebar_content").hide();
        $(activeTab).show();
        return false;
    });



    // Block search
    $('.block .block_head form .text').bind('click', function () {
        $(this).attr('value', '');
    });



    // Image actions menu
    $('ul.imglist li').hover(

    function () {
        $(this).find('ul').css('display', 'none').fadeIn('fast').css('display', 'block');
    }, function () {
        $(this).find('ul').fadeOut(100);
    });



    // Image delete confirmation
    $('ul.imglist .delete a').click(function () {
        if (confirm("Are you sure you want to delete this image?")) {
            return true;
        } else {
            return false;
        }
    });



    // Style file input
    $("input[type=file]").filestyle({
        image: "images/upload.gif",
        imageheight: 30,
        imagewidth: 80,
        width: 250
    });



    // File upload
    if ($('#fileupload').length) {
        new AjaxUpload('fileupload', {
            action: 'upload-handler.php',
            autoSubmit: true,
            name: 'userfile',
            responseType: 'text/html',
            onSubmit: function (file, ext) {
                $('.fileupload #uploadmsg').addClass('loading').text('Uploading...');
                this.disable();
            },
            onComplete: function (file, response) {
                $('.fileupload #uploadmsg').removeClass('loading').text(response);
                this.enable();
            }
        });
    }



    // Date picker
    $('input.date_picker').date_input();



    // Navigation dropdown fix for IE6
    if (jQuery.browser.version.substr(0, 1) < 7) {
        $('#header #nav li').hover(

        function () {
            $(this).addClass('iehover');
        }, function () {
            $(this).removeClass('iehover');
        });
    }


    // IE6 PNG fix
    $(document).pngFix();

});
