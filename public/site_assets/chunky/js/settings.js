function triggerChartsResize(){
    try {
        if (window.onresize){
            window.onresize();
        }
    } catch (e){
        //just swallow it
    }
    $(window).trigger('resize');
}

$(function(){
    //settings
    var $settings = $("#settings"),
        $sidebarSettings = $("#sidebar-settings"),
        settingsState = JSON.parse(localStorage.getItem("settings-state")) || {
            sidebar: 'left',
            background: 'dark',
            sidebarState: 'auto',
            displaySidebar: true
        },
        $pageHeader = $(".page-header"),
        $body = $("body"),
        popoverReallyHide = function(){
            $settings.data('bs.popover').hoverState = 'out'; //yeah. cool BS3 fix. popover programmatic APi works only on HOVER
            $settings.popover('hide');
        },
        popoverClose = function(e){
            var $popover = $settings.siblings(".popover");
            if(!$.contains($popover[0], e.target)){
                popoverReallyHide();
                $(document).off("click", popoverClose);
            }
        },
        sidebarSide = function(side){
            if (side == "right"){
                $body.addClass("sidebar-on-right")
            } else {
                $body.removeClass("sidebar-on-right")
            }
        },
        backgroundStyle = function(style){
            if (style == "dark"){
                $body.addClass("background-dark");
            } else {
                $body.removeClass("background-dark");
            }
        },
        sidebarState = function(state, triggerResize){
            var $template = $('#sidebar-settings-template');
            triggerResize = triggerResize == undefined ? true : false;
            if (!$template[0]){
                return;
            }
            $sidebarSettings.html(_.template($template.html(), {sidebarState: state}));
            if (state == "auto"){
                $(".sidebar, .side-nav, .wrap, .logo").removeClass("sidebar-icons");
            } else {
                $(".sidebar, .side-nav, .wrap, .logo").addClass("sidebar-icons");
            }
            if (triggerResize){
                triggerChartsResize();
            }

        },
        displaySidebar = function(display, triggerResize){
            triggerResize = triggerResize == undefined ? true : false;
            if (display == true){
                $body.removeClass("sidebar-hidden")
            } else {
                $body.addClass("sidebar-hidden")
            }
            if (triggerResize){
                triggerChartsResize();
            }
        };

    sidebarSide(settingsState.sidebar);
    backgroundStyle(settingsState.background);
    sidebarState(settingsState.sidebarState, false);
    displaySidebar(settingsState.displaySidebar, false);

    if (!$settings[0]){
        return;
    }

    $settings.popover({
        template: '<div class="popover settings-popover">' +
            '<div class="arrow"></div>' +
            '<div class="popover-inner">' +
            '<div class="popover-content"></div>' +
            '</div>' +
            '</div>',
        html: true,
        animation: false,
        placement: 'bottom',
        content: function(){
            return _.template($('#settings-template').html(), settingsState);
        }
    }).click(function(e){
            //close all open dropdowns
            $('.page-header .dropdown.open .dropdown-toggle').dropdown('toggle');
            // need to remove popover on anywhere-click
            $(document).on("click", popoverClose);
            $(this).focus();
            return false;
        });

    $(".page-header .dropdown-toggle").click(function(){
        popoverReallyHide()
        $(document).off("click", popoverClose);
    });
    //sidevar left/right
    $pageHeader.on("click", ".popover #sidebar-toggle .btn", function(){
        var $this = $(this),
            side = $this.data("value");
        sidebarSide(side);
        settingsState.sidebar = side;
        localStorage.setItem("settings-state", JSON.stringify(settingsState));
    });

    //background
    $pageHeader.on("click", ".popover #background-toggle .btn", function(){
        var $this = $(this),
            style = $this.data("value");
        backgroundStyle(style);
        settingsState.background = style;
        localStorage.setItem("settings-state", JSON.stringify(settingsState));
    });

    //sidebar visibility
    $pageHeader.on("click", ".popover #display-sidebar-toggle .btn", function(){
        var $this = $(this),
            display = $this.data("value");
        displaySidebar(display);
        settingsState.displaySidebar = display;
        localStorage.setItem("settings-state", JSON.stringify(settingsState));
    });

    //sidebar state {active, icons}
    $sidebarSettings.on("click", ".btn", function(){
        var $this = $(this),
            state = $this.data("value");
        if (state == 'icons'){
            closeNavigation();
        }
        sidebarState(state);
        settingsState.sidebarState = state;
        localStorage.setItem("settings-state", JSON.stringify(settingsState));
    });

    //close navigation if sidebar in icons state
    if (($("#sidebar").is(".sidebar-icons") || $(window).width() < 1049) && $(window).width() > 767){
        closeNavigation();
    }

    //imitate buttons radio behavior
    $pageHeader.on("click", ".popover [data-toggle='buttons-radio'] .btn:not(.active)", function(){
        var $this = $(this),
            $buttons = $this.parent().find('.btn');
        $buttons.removeClass('active');
        setTimeout(function(){
            $this.addClass('active');
        }, 0)
    });
});