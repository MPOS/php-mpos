<script type="text/javascript" src="{$PATH}/../global/js/number_format.js"></script>

<script>
{literal}
$(document).ready(function(){
  var audioPath = "{/literal}{$PATH}{literal}/audio/";
  var manifest = [ {id:"ding", src:"ding.ogg"} ];
  createjs.Sound.alternateExtensionseExtensions = ["mp3"];
  createjs.Sound.registerManifest(manifest, audioPath);

  // Ajax API URL
  var url_dashboard = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getdashboarddata&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var url_worker = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getuserworkers&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";
  var url_balance = "{/literal}{$smarty.server.SCRIPT_NAME}?page=api&action=getuserbalance&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}{literal}";

  // Load initial sparkline values
  var storedPersonalHashrate = [ null, null, null, null, null, null, null, null, null, null, null, null, {/literal}{$GLOBAL.userdata.hashrate|round:"2"}{literal} ];
  var storedPersonalSharerate = [ null, null, null, null, null, null, null, null, null, null, null, null, {/literal}{$GLOBAL.userdata.sharerate|round:"2"}{literal} ];
  var storedPoolHashrate = [ null, null, null, null, null, null, null, null, null, null, null, null, {/literal}{$GLOBAL.hashrate|round:"2"}{literal} ];
  var storedNetHashrate = [ null, null, null, null, null, null, null, null, null, null, null, null, {/literal}{$GLOBAL.nethashrate|round:"2"}{literal} ];
  var storedPoolWorkers = [ null, null, null, null, null, null, null, null, null, null, null, null, {/literal}{$GLOBAL.workers}{literal} ];
  var storedCoinPrice = [ null, null, null, null, null, null, null, null, null, null, null, null,
                          null, null, null, null, null, null, null, null, null, null, null, null,
                          null, null, null, null, null, null, null, null, null, null, null, null,
                          {/literal}{$GLOBAL.price}{literal} ];
  var lastBlock = 0;

  // Sparkline options applied to all graphs
  var sparklineBarOptions = {
    type: 'bar',
    height: '35',
    barWidth: 6,
    barSpacing: 2,
    chartRangeMin: 0,
    barColor: '#41fc41'
  };

  // Sparkline options applied to line graphs
  var sparklineLineOptions = {
    height: '35',
    chartRangeMin: {/literal}{$GLOBAL.price}{literal} - 5,
    chartRangeMax: {/literal}{$GLOBAL.price}{literal} + 5,
    composite: false,
    lineColor: 'black',
    fillColor: '#41fc41',
    chartRangeClip: true
  };

  // Draw our sparkline graphs with our current static content
  $('.personal-hashrate-bar').sparkline(storedPersonalHashrate, sparklineBarOptions);
  $('.personal-sharerate-bar').sparkline(storedPersonalSharerate, sparklineBarOptions);
  $('.pool-hashrate-bar').sparkline(storedPoolHashrate, sparklineBarOptions);
  $('.pool-nethashrate-bar').sparkline(storedNetHashrate, sparklineBarOptions);
  $('.pool-workers-bar').sparkline(storedPoolWorkers, sparklineBarOptions);
{/literal}{if $GLOBAL.config.price.enabled}{literal}
  $('.coin-price-line').sparkline(storedCoinPrice, sparklineLineOptions);
{/literal}{/if}{literal}

  function refreshInformation(data) {
    // Drop one value, add the latest new one to each array
    storedPersonalHashrate.shift();
    storedPersonalHashrate.push(parseFloat(data.getdashboarddata.data.personal.hashrate).toFixed(2))
    storedPersonalSharerate.shift();
    storedPersonalSharerate.push(parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2))
    storedPoolHashrate.shift();
    storedPoolHashrate.push(parseFloat(data.getdashboarddata.data.pool.hashrate).toFixed(2))
    storedNetHashrate.shift();
    storedNetHashrate.push(parseFloat(data.getdashboarddata.data.network.hashrate).toFixed(2))
    storedPoolWorkers.shift();
    storedPoolWorkers.push(parseFloat(data.getdashboarddata.data.pool.workers).toFixed(8));
    storedCoinPrice.shift();
    storedCoinPrice.push(parseFloat(data.getdashboarddata.data.pool.price).toFixed(8));
    // Redraw all bar graphs
    $('.personal-hashrate-bar').sparkline(storedPersonalHashrate, sparklineBarOptions);
    $('.personal-sharerate-bar').sparkline(storedPersonalSharerate, sparklineBarOptions);
    $('.pool-hashrate-bar').sparkline(storedPoolHashrate, sparklineBarOptions);
    $('.pool-nethashrate-bar').sparkline(storedNetHashrate, sparklineBarOptions);
    $('.pool-workers-bar').sparkline(storedPoolWorkers, sparklineBarOptions);
  {/literal}{if $GLOBAL.config.price.enabled}{literal}
    $('.coin-price-line').sparkline(storedCoinPrice, sparklineLineOptions);
  {/literal}{/if}{literal}
  }

  // Refresh other static numbers on the template
  function refreshStaticData(data) {
  {/literal}{if $GLOBAL.config.price.enabled}{literal}
    $('#b-price').html((parseFloat(data.getdashboarddata.data.pool.price).toFixed(8)));
  {/literal}{/if}{literal}
    $('#b-poolworkers').html(number_format(data.getdashboarddata.data.pool.workers));
    $('#b-hashrate').html((number_format(data.getdashboarddata.data.personal.hashrate, 2)));
    $('#b-poolhashrate').html(number_format(data.getdashboarddata.data.pool.hashrate, 2));
    if (data.getdashboarddata.data.network.hashrate > 0) {
      $('#b-nethashrate').html(number_format(data.getdashboarddata.data.network.hashrate, 2));
    } else {
      $('#b-nethashrate').html('n/a');
    }
    $('#b-sharerate').html((parseFloat(data.getdashboarddata.data.personal.sharerate).toFixed(2)));
    $('#b-yvalid').html(number_format(data.getdashboarddata.data.personal.shares.valid));
    $('#b-yivalid').html(number_format(data.getdashboarddata.data.personal.shares.invalid));
    if ( data.getdashboarddata.data.personal.shares.valid > 0 ) {
      $('#b-yefficiency').html(number_format(100 - data.getdashboarddata.data.personal.shares.invalid_percent, 2) + "%");
    } else {
      $('#b-yefficiency').html(number_format(0, 2) + "%");
    }
    $('#b-pvalid').html(number_format(data.getdashboarddata.data.pool.shares.valid));
    $('#b-pivalid').html(number_format(data.getdashboarddata.data.pool.shares.invalid));
    if ( data.getdashboarddata.data.pool.shares.valid > 0 ) {
      $('#b-pefficiency').html(number_format(100 - data.getdashboarddata.data.pool.shares.invalid_percent, 2) + "%");
    } else {
      $('#b-pefficiency').html(number_format(0, 2) + "%");
    }
    $('#b-diff').html(number_format(data.getdashboarddata.data.network.difficulty, 8));
    if (data.getdashboarddata.data.network.hashrate > 0) {
      $('#b-nextdiff').html(number_format(data.getdashboarddata.data.network.nextdifficulty, 8));
      $('#b-nextdiffc').html(" Change in " + data.getdashboarddata.data.network.blocksuntildiffchange + " Blocks");
    } else {
      $('#b-nextdiff').html('n/a');
      $('#b-nextdiffc').html(' No Estimates');
    }
    $('#b-esttimeperblock').html(data.getdashboarddata.data.pool.esttimeperblock.toHHMMSS());
    $('#b-nblock').html(data.getdashboarddata.data.network.block);
    $('#b-roundprogress').html(number_format(parseFloat(data.getdashboarddata.data.pool.shares.progress).toFixed(2), 2) + "%");
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

  // Refresh worker information
  function refreshWorkerData(data) {
    workers = data.getuserworkers.data;
    length = workers.length;
    totalHashrate = 0;
    $('#b-workers').html('');
    for (var i = j = 0; i < length; i++) {
      if (workers[i].hashrate > 0) {
        totalHashrate += workers[i].hashrate;
        j++;
        $('#b-workers').append('<tr><td class="text-left">' + workers[i].username + '</td><td class="text-right">' + number_format(workers[i].hashrate, 2) + '</td><td class="text-right">' + workers[i].difficulty + '</td></tr>');
      }
    }
    if (j == 0) { $('#b-workers').html('<tr><td colspan="3" class="text-center">No active workers</td></tr>'); }
    if (totalHashrate > 0) { $('#b-workers').append('<tr><td class="text-left"><b>Total</b></td><td class="text-right">' + number_format(totalHashrate, 2) + '</td><td></td></tr>'); }
  }

  function refreshBlockData(data) {
    blocks = data.getdashboarddata.data.pool.blocks;
    if (blocks.length <= 0) return;
    // Initilize
    if (lastBlock == 0) {
      lastBlock = blocks[0].height;
      return;
    }
    if (blocks[0].height > lastBlock) {
      createjs.Sound.play('ding');
      lastBlock = blocks[0].height;
      var table_content = '<tbody id="b-blocks">';
      for (index = 0; index < blocks.length; ++index) {
        var time = new Date(blocks[index].time * 1000)
        var table_row = '<tr>';
        table_row += '<td class="text-right">' + blocks[index].height + '</td>';
        table_row += '<td class="text-center">' + blocks[index].finder + '</td>';
        table_row += '<td class="text-right">' + time.format("mm/dd/yyyy HH:MM:ss") + '</td>';
        table_row += '<td class="text-right">' + parseFloat(blocks[index].difficulty).toFixed(4) + '</td>';
        table_row += '<td class="text-right">' + parseFloat(blocks[index].amount).toFixed(2) + '</td>';
        table_row += '<td class="text-right">' + number_format(blocks[index].estshares) + '</td>';
        table_row += '<td class="text-right">' + number_format(blocks[index].shares) + '</td>';
        percentage = parseFloat(blocks[index].shares / blocks[index].estshares * 100).toFixed(2);
        if (percentage <= 100) {
          color = 'green';
        } else {
          color = 'red';
        }
        table_row += '<td class="text-right"><font color="'+color+'">' + percentage + '</font></td>';
        table_row += '</tr>';
        table_content += table_row;
      }
      table_content += '</tbody>';
      $("tbody#b-blocks").replaceWith(table_content);
    }
  }

  // Refresh balance information
  function refreshBalanceData(data) {
    balance = data.getuserbalance.data
    $('#b-confirmed').html(number_format(balance.confirmed, 6));
    $('#b-unconfirmed').html(number_format(balance.unconfirmed, 6));
  }

  // Worker progess for overview graphs
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
        refreshBlockData(data);
      },
      complete: function() {
        setTimeout(worker1, {/literal}{($GLOBAL.config.statistics_ajax_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();

  // Worker process to update active workers in the account details table
  (function worker2() {
    $.ajax({
      url: url_worker,
      dataType: 'json',
      cache : false,
      contentType : 'application/json; charset=utf-8',
      type : 'GET',
      success: function(data) {
        refreshWorkerData(data);
      },
      complete: function() {
        setTimeout(worker2, {/literal}{($GLOBAL.config.statistics_ajax_long_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();

  // Worker process to update user account balances
  // Our worker process to keep worker information updated
  (function worker3() {
    $.ajax({
      url: url_balance,
      dataType: 'json',
      success: function(data) {
        refreshBalanceData(data);
      },
      complete: function() {
        setTimeout(worker3, {/literal}{($GLOBAL.config.statistics_ajax_long_refresh_interval * 1000)|default:"10000"}{literal})
      }
    });
  })();
});
{/literal}
</script>
