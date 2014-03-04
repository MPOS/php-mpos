<script type="text/javascript" src="{$PATH}/../global/js/number_format.js"></script>

<script>
{literal}
$(document).ready(function(){
  // Ajax API URL
  var url_dashboard = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var url_worker = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getuserworkers&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var url_balance = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getuserbalance&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";

  // Load initial sparkline values
  var storedPersonalHashrate = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {/literal}{$GLOBAL.userdata.hashrate|number_format:"2"}{literal} ];
  var storedPersonalSharerate = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {/literal}{$GLOBAL.userdata.sharerate|number_format:"2"}{literal} ];
  var storedPoolHashrate = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {/literal}{$GLOBAL.hashrate|number_format:"2"}{literal} ];
  var storedPoolWorkers = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {/literal}{$GLOBAL.workers}{literal} ];
  var storedCoinPrice = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, {/literal}{$GLOBAL.price}{literal} ];

  // Sparkline options applied to all graphs
  var sparklineBarOptions = {
    type: 'bar',
    height: '35',
    barWidth: 6,
    barSpacing: 2,
    chartRangeMin: 0
  };

  // Sparkline options applied to line graphs
  var sparklineLineOptions = {
    height: '35',
    chartRangeMin: 0,
    composite: false,
    lineColor: 'black'
  };

  // Draw our sparkline graphs with our current static content
  $('.personal-hashrate-bar').sparkline(storedPersonalHashrate, sparklineBarOptions);
  $('.personal-sharerate-bar').sparkline(storedPersonalSharerate, sparklineBarOptions);
  $('.pool-hashrate-bar').sparkline(storedPoolHashrate, sparklineBarOptions);
  $('.pool-workers-bar').sparkline(storedPoolWorkers, sparklineBarOptions);
  $('.coin-price-line').sparkline(storedCoinPrice, sparklineLineOptions);

  function refreshInformation(data) {
    // Drop one value, add the latest new one to each array
    storedPersonalHashrate.shift();
    storedPersonalHashrate.push(parseFloat(data.getdashboarddata.data.personal.hashrate).toFixed(2))
    storedPersonalSharerate.shift();
    storedPersonalSharerate.push(parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2))
    storedPoolHashrate.shift();
    storedPoolHashrate.push(parseFloat(data.getdashboarddata.data.pool.hashrate).toFixed(2))
    storedPoolWorkers.shift();
    storedPoolWorkers.push(parseFloat(data.getdashboarddata.data.pool.workers).toFixed(8));
    storedCoinPrice.shift();
    storedCoinPrice.push(parseFloat(data.getdashboarddata.data.pool.price).toFixed(8));
    // Redraw all bar graphs
    $('.personal-hashrate-bar').sparkline(storedPersonalHashrate, sparklineBarOptions);
    $('.personal-sharerate-bar').sparkline(storedPersonalSharerate, sparklineBarOptions);
    $('.pool-hashrate-bar').sparkline(storedPoolHashrate, sparklineBarOptions);
    $('.pool-workers-bar').sparkline(storedPoolWorkers, sparklineBarOptions);
    $('.coin-price-line').sparkline(storedCoinPrice, sparklineLineOptions);
  }

  // Refresh other static numbers on the template
  function refreshStaticData(data) {
    $('#b-price').html((parseFloat(data.getdashboarddata.data.pool.price).toFixed(8)));
    $('#b-poolworkers').html(data.getdashboarddata.data.pool.workers);
    $('#b-hashrate').html((parseFloat(data.getdashboarddata.data.personal.hashrate).toFixed(2)));
    $('#b-poolhashrate').html((parseFloat(data.getdashboarddata.data.pool.hashrate).toFixed(2)));
    $('#b-sharerate').html((parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2)));
    $('#b-yvalid').html(number_format(data.getdashboarddata.data.personal.shares.valid));
    $('#b-yivalid').html(number_format(data.getdashboarddata.data.personal.shares.invalid));
    $('#b-yefficiency').html(number_format(100 - data.getdashboarddata.data.personal.shares.invalid_percent, 2) + "%");
    $('#b-pvalid').html(number_format(data.getdashboarddata.data.pool.shares.valid));
    $('#b-pivalid').html(number_format(data.getdashboarddata.data.pool.shares.invalid));
    $('#b-pefficiency').html(number_format(100 - data.getdashboarddata.data.pool.shares.invalid_percent, 2) + "%");
    $('#b-diff').html(number_format(data.getdashboarddata.data.network.difficulty, 8));
    $('#b-nextdiff').html(number_format(data.getdashboarddata.data.network.nextdifficulty, 8) + " (Change in " + data.getdashboarddata.data.network.blocksuntildiffchange + " Blocks)");
    var minutes = Math.floor(data.getdashboarddata.data.network.esttimeperblock / 60);
    var seconds = Math.floor(data.getdashboarddata.data.network.esttimeperblock - minutes * 60);
    $('#b-esttimeperblock').html(minutes + " minutes " + seconds + " seconds"); // <- this needs some nicer format
    $('#b-nblock').html(data.getdashboarddata.data.network.block);
    $('#b-roundprogress').html(number_format(data.getdashboarddata.data.pool.shares.progress) + "%");
    {/literal}{if $GLOBAL.config.payout_system != 'pps'}{literal }
    $('#b-payout').html(number_format(data.getdashboarddata.data.personal.estimates.payout, 8));
    $('#b-block').html(number_format(data.getdashboarddata.data.personal.estimates.block, 8));
    $('#b-fee').html(number_format(data.getdashboarddata.data.personal.estimates.fee,8 ));
    $('#b-donation').html(number_format(data.getdashboarddata.data.personal.estimates.donation, 8));
{/literal}{else}{literal}
    $('#b-ppsunpaid').html(number_format(data.getdashboarddata.data.personal.shares.unpaid));
    $('#b-ppsdiff').html(number_format(data.getdashboarddata.data.personal.sharedifficulty, 2));
    $('#b-est1').html(number_format(data.getdashboarddata.data.personal.estimates.hours1, 8));
    $('#b-est24hours').html(number_format(data.getdashboarddata.data.personal.estimates.hours24, 8));
    $('#b-est7days').html(number_format(data.getdashboarddata.data.personal.estimates.days7, 8));
    $('#b-est14days').html(number_format(data.getdashboarddata.data.personal.estimates.days14, 8));
    $('#b-est30days').html(number_format(data.getdashboarddata.data.personal.estimates.days30, 8));
{/literal}{/if}{literal}
{/literal}{if $GLOBAL.config.payout_system == 'pplns'}{literal}
    $('#b-pplns').html({/literal}{$GLOBAL.pplns.target}{literal});
{/literal}{/if}{literal}
  }

  // Our worker process to keep gauges and graph updated
  (function worker1() {
    $.ajax({
      url: url_dashboard,
      dataType: 'json',
      cache : false,
      contentType : 'application/json; charset=utf-8',
      type : 'GET',
      success: function(data) {
        refreshInformation(data);
        refreshStaticData(data);
      },
      complete: function() {
        setTimeout(worker1, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
