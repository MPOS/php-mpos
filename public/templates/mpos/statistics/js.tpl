<script>
{literal}
$(document).ready(function(){

  // Ajax API URL
  var url = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getnavbardata{literal}";

  function refreshStaticData(data) {
     $('#b-workers').html((parseFloat(data.getnavbardata.data.pool.workers).toFixed(0)));
     $('#b-hashrate').html((parseFloat(data.getnavbardata.data.pool.hashrate).toFixed(3)));
     $('#b-target').html(data.getnavbardata.data.pool.estimated + " (done: " + data.getnavbardata.data.pool.progress + "%)");
     $('#b-diff').html(data.getnavbardata.data.network.difficulty);
  }

  // Our worker process to keep gauges and graph updated
  (function worker() {
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(data) {
        refreshStaticData(data);
      },
      complete: function() {
        setTimeout(worker, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
     }
   });
 })();
});
{/literal}
</script>
