// Several JS Global Classes
$(document).ready(function() {
  
  // Make all tables with database class sortable
  $('.datatable').dataTable();

  // Bootstrap iOS style switches for checkboxes with switch class
  $('.switch').bootstrapSwitch();

  if (document.getElementById("motd")) {
    var md5motd = $.md5(document.getElementById('motd').innerHTML);
    // Check if MOTD alert has been closed
    if( $.cookie('motd-box') === md5motd ){
      $('#motd').hide();
    }
  }

  if (document.getElementById("lastlogin")) {
    var md5lastlogin = $.md5(document.getElementById('lastlogin').innerHTML);
    // Check if lastlogin alert has been closed
    if( $.cookie('lastlogin-box') === md5lastlogin ){
      $('#lastlogin').hide();
    }
  }

  if (document.getElementById("backend")) {
    var md5backend = $.md5(document.getElementById('backend').innerHTML);
    // Check if Backend Issues alert has been closed
    if( $.cookie('backend-box') === md5backend ){
      $('#backend').hide();
    }
  }
    
});

$(function() {

  // Grab your button (based on your posted html)
  $('.uk-close').click(function( e ){
    e.preventDefault();
    var id = $(this).closest("div").attr("id");
    console.log(id);
    if (id === 'motd') {
      var md5motd = $.md5(document.getElementById('motd').innerHTML);
      $.cookie('motd-box', md5motd, { path: '/' });
    } else if (id === 'lastlogin') {
      var md5lastlogin = $.md5(document.getElementById('lastlogin').innerHTML);
      $.cookie('lastlogin-box', md5lastlogin, { path: '/' });
    } else if (id === 'backend') {
      var md5backend = $.md5(document.getElementById('backend').innerHTML);
      $.cookie('backend-box', md5backend, { path: '/' });
    } else {
      //alert(id);
    }
  });

});
