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
    
    if (document.getElementById("motd")) {
    	var md5motd = $.md5(document.getElementById('motd').innerHTML);
		// Check if MOTD alert has been closed
		//alert(md5motd);
    	if( $.cookie('motd-box') === md5motd ){
        	$('#motd').hide();
        	//alert('hidden');
    	}
    }
    
    if (document.getElementById("lastlogin")) {
    	var md5lastlogin = $.md5(document.getElementById('lastlogin').innerHTML);
    	// Check if lastlogin alert has been closed
    	//alert(md5lastlogin);
    	if( $.cookie('lastlogin-box') === md5lastlogin ){
        	$('#lastlogin').hide();
        	//alert('hidden');
    	}
    
    }

    if (document.getElementById("backend")) {
    	var md5backend = $.md5(document.getElementById('backend').innerHTML);
    	// Check if Backend Issues alert has been closed
    	//alert(md5backend);
    	if( $.cookie('backend-box') === md5backend ){
        	$('#backend').hide();
        	//alert('hidden');
    	}
    }
    
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
   
    // Grab your button (based on your posted html)
    $('.close').click(function( e ){
        e.preventDefault();
        //alert($(this).attr("id"));
        if ($(this).attr("id") === 'motd') {
        	var md5motd = $.md5(document.getElementById('motd').innerHTML);
        	$.cookie('motd-box', md5motd, { path: '/' });
        } else if ($(this).attr("id") === 'lastlogin') {
        	var md5lastlogin = $.md5(document.getElementById('lastlogin').innerHTML);
        	$.cookie('lastlogin-box', md5lastlogin, { path: '/' });
        } else if ($(this).attr("id") === 'backend') {
        	var md5backend = $.md5(document.getElementById('backend').innerHTML);
        	$.cookie('backend-box', md5backend, { path: '/' });
        } else {
            //alert($(this).attr("id"));
        }
    });

});
