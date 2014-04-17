
<div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"><i class="fa fa-clock-o fa-fw"></i> UptimeRobot Status</div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Location</th>
              <th class="text-center">Service</th>
              <th class="text-center">Status</th>
              <th class="text-center">Status Since</th>
              <th class="text-center col-sm-2">Day</th>
              <th class="text-center col-sm-2">Week</th>
              <th class="text-center col-sm-2">Month</th>
              <th class="text-center col-sm-2">All Time</th>
            </tr>
          </thead>
          <tbody>
      {foreach key=key item=item from=$STATUS}
      {assign var=node value="."|explode:$item.friendlyname}
            <tr>
              <td class="text-center"><img src="{$GLOBALASSETS}/images/flags/{$node.0}.png"/></td>
              {if $node|count > 1}<td class="text-center">{$node.1}</td>{/if}
              <td class="text-center"><span class="ur-status-{$CODES[$item.status]|lower}">{$CODES[$item.status]}</span></td>
              <td class="text-center">{$item.log.1.datetime|date_format:$GLOBAL.config.date}</td>
              <td>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$item.customuptimeratio.0}" aria-valuemin="0" aria-valuemax="100" style="width: {$item.customuptimeratio.0}%">
                    <span class="sr-only">{$item.customuptimeratio.0}% up</span>
                  </div>
                </div>
              </td>
              <td>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$item.customuptimeratio.1}" aria-valuemin="0" aria-valuemax="100" style="width: {$item.customuptimeratio.1}%">
                    <span class="sr-only">{$item.customuptimeratio.1}% up</span>
                  </div>
                </div>
              </td>
              <td>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$item.customuptimeratio.2}" aria-valuemin="0" aria-valuemax="100" style="width: {$item.customuptimeratio.2}%">
                    <span class="sr-only">{$item.customuptimeratio.2}% up</span>
                  </div>
                </div>
              </td>
              <td>
                <div class="progress progress-striped active">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$item.alltimeuptimeratio}" aria-valuemin="0" aria-valuemax="100" style="width: {$item.alltimeuptimeratio}%">
                    <span class="sr-only">{$item.alltimeuptimeratio}% up</span>
                  </div>
                </div>
              </td>
            </tr>
      {/foreach}
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        <h6>Last update {$UPDATED|date_format:$GLOBAL.config.date}</h6>
      </div>
    </div>
  </div>
