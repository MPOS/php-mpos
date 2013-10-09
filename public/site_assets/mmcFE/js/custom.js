$(function () {

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

        // hack to statically set width as something is broken with div width calculation - anni
        var chart_width = $(document).width() - 400;

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
                // 'bar', 'area', 'pie', 'line'
                width: chart_width,
                type: statsType,
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

    $("table.pagesort")
      .tablesorter({ widgets: ['zebra'] })
      .tablesorterPager({ positionFixed: false, container: $("#pager") });
    $("table.pagesort2")
      .tablesorter({ widgets: ['zebra'] })
      .tablesorterPager({ positionFixed: false, container: $("#pager2") });
    $("table.pagesort4")
      .tablesorter({ widgets: ['zebra'] })
      .tablesorterPager({ positionFixed: false, container: $("#pager3") });

    $('.block table tr th.header').css('cursor', 'pointer');

    // Check / uncheck all checkboxes
    $('.check_all').click(function () {
        $(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));
    });

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

    // Navigation dropdown fix for IE6
    if (jQuery.browser.version.substr(0, 1) < 7) {
        $('#header #nav li').hover(

        function () {
            $(this).addClass('iehover');
        }, function () {
            $(this).removeClass('iehover');
        });
    }

});
