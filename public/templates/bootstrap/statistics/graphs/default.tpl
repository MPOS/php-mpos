<script>
$(function () {

  // needed for automatic activation of first tab
  $(function () {
    $('#hashrategraph a:first').tab('show')
  })

  // You can't draw here chart directly, because it's on hidden tab, instead let's do the workaround
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    // this ain't pretty, but you should get the idea
    if ($(e.target).attr('href') == '#pool' && $('#pool-area-chart').html().length == 0) {
      Morris.Area({
        element: 'pool-area-chart',
        data: [
        {foreach $POOLHASHRATES as $hour=>$hashrate}
        {
        period: '{$hour|default:"0"}:00',
        Pool: '{$hashrate|default:"0"}',
        },
        {/foreach}
        ],
        parseTime: false,
        behaveLikeLine: true,
        xkey: 'period',
        ykeys: ['Pool'],
        labels: ['Hashrate'],
        pointSize: 2,
        hideHover: 'auto',
        lineColors: ['#0b62a4'],
        pointFillColors: ['#FFFFFF'],
        resize: true,
        fillOpacity: 1.00,
        postUnits: ' KH/s'
      });
    }
    
    if ($(e.target).attr('href') == '#mine' && $('#mine-area-chart').html().length == 0) {
      Morris.Area({
        element: 'mine-area-chart',
        data: [
        {foreach $YOURHASHRATES as $yourhour=>$yourhashrate}
        {
        period: '{$yourhour|default:"0"}:00',
        Mine: '{$yourhashrate|default:"0"}',
        },
        {/foreach}
        ],
        parseTime: false,
        behaveLikeLine: true,
        xkey: 'period',
        ykeys: ['Mine'],
        labels: ['Hashrate'],
        pointSize: 2,
        hideHover: 'auto',
        lineColors: ['#24A665'],
        pointFillColors: ['#FFFFFF'],
        resize: true,
        fillOpacity: 1.00,
        postUnits: ' KH/s'
      });
    }

    if ($(e.target).attr('href') == '#both' && $('#both-area-chart').html().length == 0) {
      Morris.Area({
        element: 'both-area-chart',
        data: [
        {foreach $YOURHASHRATES as $yourhour=>$yourhashrate}
        {
        period: '{$yourhour|default:"0"}:00',
        Mine: '{$yourhashrate|default:"0"}',
          {foreach $POOLHASHRATES as $poolhour=>$poolhashrate}
          {if $yourhour eq $poolhour}
          Pool: '{$poolhashrate|default:"0"}',
          {/if}
          {/foreach}
        },
        {/foreach}
        ],
        parseTime: false,
        behaveLikeLine: true,
        xkey: 'period',
        ykeys: ['Mine', 'Pool'],
        labels: ['Your Hashrate', 'Pool Hashrate'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true,
        fillOpacity: 0.1,
        postUnits: ' KH/s'
      });
    }
    
  });
});
</script>

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-signal fa-fw"></i> Stats
      </div>           
      <div class="panel-body">
        <ul class="nav nav-pills" id="hashrategraph">
          <li><a href="#mine" data-toggle="tab">Mine</a></li>
          <li><a href="#pool" data-toggle="tab">Pool</a></li>
          <li><a href="#both" data-toggle="tab">Both</a></li>
        </ul>
        <div class="tab-content">
          {include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/mine.tpl"}
          {include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/pool.tpl"}
          {include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/both.tpl"}
        </div>
      </div>
    </div>
  </div>
</div>
