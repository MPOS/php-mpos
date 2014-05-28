var diff_to_compare = 100000000

      var color_table = { 
        0 : "#22ff22",    // bright green
        1 : "#88ff88",    // pale green
        2 : "#FFFFFF",    // white
        3 : "#FFFF88",    // pale yellow
        4 : "#FFFF22",    // bright yellow
        5 : "#FF2222",    // pale red
        6 : "#FF6666"     // red 
      }
      var block_colors = {
        "confirmed" : color_table[0],
        "submitted" : color_table[2], 
        "verifying" : color_table[1], 
        "orphaned" :  color_table[6]
      }
      var white = color_table[2]

function formatDiffString(diff) { 
  var curDiffstr = (diff /  1e6);
  curDiffstr = curDiffstr.toFixed(2);
  return curDiffstr
}
function highlight(element, color, duration) { 
  element.css("backgroundColor", white) 
  element.animate({backgroundColor: color}, 1500)
  setTimeout(function(){ element.animate( { backgroundColor: white }, 
  duration == undefined ? 3000: duration)}, 1000)
}

  
function updateDiffElement(element, data) {
  diffToCompare = data.sharediff
  var curDiffstr = formatDiffString(data.sharediff) 
  element.text(curDiffstr+'M ('+(Math.floor(data.diff))+')');
  highlight(element, "FFFF22", 3000)
}
