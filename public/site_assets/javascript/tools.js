/*
    --------------------------------------------------------------------------
    Code for link-hover text boxes
    By Nicolas Hoening (Web Site: http://nicolashoening.de)
    --------------------------------------------------------------------------
*/

// create the popup box - remember to give it some width in your styling 
document.write('<div id="popup" style="position:abolute; z-index:200;"></div>');
var minMarginToBorder = 15; // set how much minimal space there should be to
// the next border (horizontally)
var ready = false; // we are ready when the mouse is being caught

$(document).ready(function(){
    $('#popup').hide();

    // set dynamic coords when the mouse moves
    $(document).mousemove(function(e){ 
        var x,y;
      
        x = $(document).scrollLeft() + e.clientX;
        y = $(document).scrollTop() + e.clientY;

        x += 10; // important: if the popup is where the mouse is, the hoverOver/hoverOut events flicker
      
        var x_y = nudge(x,y); // avoids edge overflow
      
        // remember: the popup is still hidden
        $('#popup').css('top', x_y[1] + 'px');
        $('#popup').css('left', x_y[0] + 'px');

        ready = true;
    });
});

// avoid edge overflow
function nudge(x,y)
{
    var win = $(window);
    // When the mouse is too far on the right, put window to the left
    var xtreme =$(document).scrollLeft() + win.width() - $('#popup').width() - minMarginToBorder;
    if(x > xtreme) {
        x -= $('#popup').width() + minMarginToBorder + 20;
    }
    x = max(x, 0)

    // When the mouse is too close to the bottom, move it up.
    // I estimate the lines that fit in the width, assuming (a little pessimisticly) 
    // a char width of 15 pixels and a line height of 20 (That should work for most cases)
    // Unfortunately, I cannot read margin and padding to get even better values, 
    // since JS can only read what is set before itself, apparently. This works quite well 
    // with a padding of 5px.
    est_lines = parseInt($('#popup').html().length / (parseInt($('#popup').width())/15) );
    est_lines_to_decide = max(est_lines, 2);
    if((y + parseInt(est_lines_to_decide * 20)) > (win.height() +  $(document).scrollTop())) {
        y -= parseInt(est_lines * 20) + 20;
    }

    return [ x, y ];
}

// write content and display
function popup(planet_name)
{
    if (ready) {
        $.ajax({
            url: 'index.php?page=api&action=starmap&planetname='+planet_name,
            cache: true,
            success: function(data) {
                $('#popup').html(data).show();
            }
        });
    }
}

// make content box invisible
function kill()
{
    $('#popup').hide();
}


function max(a,b){
    if (a>b) return a;
    else return b;
}

$(document).ready(function(){
    $("div#panel").hide();
    $("#toggle").click(function(){
        $("#panel").slideToggle("slow");
        $(this).toggleClass("active");
        return false;
    });
	
	 
});