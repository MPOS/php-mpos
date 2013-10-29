<script type="text/javascript" src="{$GLOBALASSETS}/js/jquery.easypiechart.min.js"></script>

<article class="module width_half">
  <header><h3>UptimeRobot Status</h3></header>
  <table class="tablesorter" width="80%" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Location</th>
        <th align="center">Service</th>
        <th align="center">Status</th>
        <th align="center">Status Since</th>
        <th align="center" style="padding-right: 10px">Uptime</th>
      </tr>
    </thead>
    <tbody>
{foreach key=key item=item from=$STATUS.monitors.monitor}
{assign var=node value="."|explode:$item.friendlyname}
      <tr>
        <td align="center"><img src="{$GLOBALASSETS}/images/flags/{$node.0}.png"/></td>
        <td align="center">{$node.1}</td>
        <td align="center"><span class="ur-status-{$CODES[$item.status]|lower}">{$CODES[$item.status]}</span></td>
        <td align="center">{$item.log.1.datetime|date_format:"%b %d, %Y %H:%M"}</td>
        <td align="center"><span class="chart-{$item.id}" data-percent="{$item.customuptimeratio}"><span class="percent"></span></span></td>
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
{/literal}
{foreach key=key item=item from=$STATUS.monitors.monitor}
{literal}
  $('.chart-{/literal}{$item.id}{literal}').easyPieChart({
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
{/literal}
{/foreach}
{literal}
});
{/literal}
</script>
