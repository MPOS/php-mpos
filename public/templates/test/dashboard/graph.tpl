<article class="module width_half">
  <header><h3>Graphs</h3></header>
  <div class="module_content">
	<div id="hashrategraph" style="height:250px;width:600px; "></div>
  </div>
</article>

<script>{literal}
$(document).ready(function(){
  var url = "{/literal}{$smarty.server.PHP_SELF}?page=api&action=getuserhashrate&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var storedData = Array();
  var options = {
    highlighter: { tooltipAxes: 'both', show: true },
    title: 'Hashrate',
    axes: {
      yaxis:{ min:0, pad: 0, padMax: 1.1},
      xaxis:{ min:0, max: 59, tickInterval: 5, pad: 0},
    },
  };
  console.log(options);
  for (var i = 0; i < 59; i++) { storedData[i] = [i, 0] }
  var d = new Date();
  storedData[d.getMinutes()] = [ d.getMinutes(), {/literal}{$GLOBAL.userdata.hashrate}{literal} ];
  $.jqplot('hashrategraph', [storedData], options); 
  setInterval(function() {
    $.ajax({
      // have to use synchronous here, else the function 
      // will return before the data is fetched
      async: false,
      url: url,
      dataType: "json",
      success: function(data) {
  	var d = new Date();
        storedData[d.getMinutes()] = [d.getMinutes(), data.getuserhashrate.hashrate];
        $.jqplot('hashrategraph', [storedData], options).replot();
      }
    });
  }, 25000);
});
{/literal}</script>
