$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        // console.log($(this).width())
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
})

// Several JS Glocal Classes
$(document).ready(function() {
    // Make all tables with database class sortable
    $('.datatable').dataTable();

    // Bootstrap iOS style switches for checkboxes with switch class
    $('.switch').bootstrapSwitch();

});

$(function() {

	// auto hide notification messages if set in config
	// starting timeout in ms before first message is hidden
    var hide_delay = 3000;
    // time in ms to wait before hiding next message
    var hide_next = 800;
    $(".autohide").slideDown().each( function(index,el) {
        window.setTimeout( function(){
        	$(el).slideUp();  // hide the message
        }, hide_delay + hide_next*index);
    });
   
    // Check if lastlogin alert has been closed
    if( $.cookie('lastlogin-box') === 'closed' ){
        $('#lastlogin').hide();
    }
    // Check if MOTD alert has been closed
    if( $.cookie('motd-box') === 'closed' ){
        $('#motd').hide();
    }
    // Check if Backend Issues alert has been closed
    if( $.cookie('backend-box') === 'closed' ){
        $('#backend').hide();
    }
    
    // Grab your button (based on your posted html)
    $('.close').click(function( e ){
        e.preventDefault();
        //alert($(this).attr("id"));
        if ($(this).attr("id") === 'motd') {
        	$.cookie('motd-box', 'closed', { path: '/' });
        } else if ($(this).attr("id") === 'lastlogin') {
        	$.cookie('lastlogin-box', 'closed', { path: '/' });
        } else if ($(this).attr("id") === 'backend') {
        	$.cookie('backend-box', 'closed', { path: '/' });
        } else {
            //alert($(this).attr("id"));
        }
    });

});
