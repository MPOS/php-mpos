<script type="text/javascript" src="{$GLOBALASSETS}/js/jquery.easypiechart.min.js"></script>

{include file="global/block_header.tpl" BLOCK_HEADER="Uptime Statistics"}
  <table class="tablesorter" width="80%" cellspacing="0">
    <thead>
      <tr>
        <th class="center">Location</th>
        <th class="center">Service</th>
        <th class="center">State Since</th>
        <th class="center">Status</th>
        <th class="center" colspan="4" style="padding-right: 10px">Uptime</th>
      </tr>
      <tr>
        <th colspan="4"></th>
        <th>Day</th>
        <th>Week</th>
        <th>Month</th>
        <th>All Time</th>
      </tr>
    </thead>
    <tbody>
{foreach key=key item=item from=$STATUS}
{assign var=node value="."|explode:$item.friendlyname}
      <tr>
        <td class="center"><img src="{$GLOBALASSETS}/images/flags/{$node.0}.png"/></td>
        <td class="center">{$node.1}</td>
        <td class="center">{$item.log.1.datetime|date_format:"%b %d, %Y %H:%M"}</td>
        <td class="center"><span class="ur-status-{$CODES[$item.status]|lower}">{$CODES[$item.status]}</span></td>
        <td class="center"><span class="chart" data-percent="{$item.customuptimeratio.0}"><span class="percent"></span></span></td>
        <td class="center"><span class="chart" data-percent="{$item.customuptimeratio.1}"><span class="percent"></span></span></td>
        <td class="center"><span class="chart" data-percent="{$item.customuptimeratio.2}"><span class="percent"></span></span></td>
        <td class="center"><span class="chart" data-percent="{$item.alltimeuptimeratio}"><span class="percent"></span></span></td>
      </tr>
{/foreach}
    </tbody>
  </table>
  <ul><li>Last update {$UPDATED|date_format:"%b %d, %Y %H:%M"}</li></ul>

<script>
{literal}
$(document).ready(function(){
  $('.chart').easyPieChart({
    easing: 'easeOutBounce',
    size: 26,
    scaleColor: false,
    lineWidth: 13,
    lineCap: 'butt',
    barColor: '#92CCA6',
    trackColor: '#FF7878',
    animate: false,
    onStep: function(from, to, percent) {
      $(this.el).find('.percent-{/literal}{$item.id}{literal}').text(Math.round(percent));
    }
  });
});
{/literal}
</script>
{include file="global/block_footer.tpl"}
