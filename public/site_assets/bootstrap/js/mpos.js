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

    // Check if lastlogin alert has been closed
    if( $.cookie('lastlogin-box') === 'closed' ){
        $('#lastlogin').hide();
    }
    
    // Grab your button (based on your posted html)
    $('.close').click(function( e ){
        e.preventDefault();
        //alert($(this).attr("id"));
        $.cookie('lastlogin-box', 'closed', { path: '/' });
    });

});