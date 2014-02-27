$(document).ready(function() {
  $(".tablesorter").tablesorter();
  $(".tablesorterpager").tablesorter().tablesorterPager({positionFixed: false, container: $("#pager"), cssNext: ".icon-forward", cssPrev: ".icon-backward", cssFirst: ".icon-fast-backward", cssLast: ".icon-fast-forward"});
  $(".tab_content").hide(); //Hide all content
  $("ul.tabs li:first").addClass("active").show(); //Activate first tab
  $(".tab_content:first").show(); //Show first tab content
  $("ul.tabs li").click(function() {
    $("ul.tabs li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    $(".tab_content").hide(); //Hide all tab content
    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active ID content
    $(activeTab).find('.visualize').trigger('visualizeRefresh');
    return false;
  });
  $('table.visualize').each(function () {
    if ($(this).attr('rel')) {
      var statsType = $(this).attr('rel');
    } else {
      var statsType = 'area';
    }

    // hack to statically set width as something is broken with div width calculation - anni
    var chart_width = $(document).width() - 500;

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
});
