<script type="text/javascript" src="{$GLOBALASSETS}/js/jquery.easypiechart.min.js"></script>

<article class="module width_half">
  <header><h3>UptimeRobot Status</h3></header>
  <table class="tablesorter" width="80%" cellspacing="0">
    <thead>
      <tr>
        <th>Location</th>
        <th>Service</th>
        <th>Status</th>
        <th>Status Since</th>
        <th colspan="4" style="padding-right: 10px">Uptime</th>
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
        <td><img src="{$GLOBALASSETS}/images/flags/{$node.0}.png"/></td>
        {if $node|count > 1}<td>{$node.1}</td>{/if}
        <td><span class="ur-status-{$CODES[$item.status]|lower}">{$CODES[$item.status]}</span></td>
        <td>{$item.log.1.datetime|date_format:"%b %d, %Y %H:%M"}</td>
        <td><span class="chart" data-percent="{$item.customuptimeratio.0}"><span class="percent"></span></span></td>
        <td><span class="chart" data-percent="{$item.customuptimeratio.1}"><span class="percent"></span></span></td>
        <td><span class="chart" data-percent="{$item.customuptimeratio.2}"><span class="percent"></span></span></td>
        <td><span class="chart" data-percent="{$item.alltimeuptimeratio}"><span class="percent"></span></span></td>
      </tr>
{/foreach}
    </tbody>
  </table>
  <footer>
    <ul><li>Last update {$UPDATED|date_format:"%b %d, %Y %H:%M"}</li></ul>
  </footer>
</article>

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
