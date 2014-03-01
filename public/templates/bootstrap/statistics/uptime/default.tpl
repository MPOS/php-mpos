
<div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading"><i class="fa fa-clock-o fa-fw"></i> UptimeRobot Status</div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Location</th>
              <th>Service</th>
              <th>Status</th>
              <th>Status Since</th>
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
      </div>
      <div class="panel-footer">
        Last update {$UPDATED|date_format:"%b %d, %Y %H:%M"}
      </div>
    </div>
  </div>
