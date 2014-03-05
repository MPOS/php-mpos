  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-align-left fa-fw"></i> Round statistics
        </h4>
      </div>
      <div class="panel-footer">
        <div class="row text-center">
          <div class="col-xs-4 b-r">
            <p id="b-nblock" class="h4 font-bold m-t">{$NETWORK.block}</p>
            <p class="text-muted">Current Block</p>
          </div>
          <div class="col-xs-4 b-r">
            <p id="b-roundprogress" class="h4 font-bold m-t">{$ESTIMATES.percent|number_format:"2"}%</p>
            <p class="text-muted">Of Expected Shares</p>
          </div>
          <div class="col-xs-4 b-r">
            <p id="b-payout" class="h4 font-bold m-t">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</p>
            <p class="text-muted">Estimated Earnings</p>
          </div>
        </div>
      </div>
      <table class="table m-b-none text-small">
        <thead>
          <tr>
            <th><h4><i class="fa fa-cloud fa-hw"></i> Round Shares</h4></th>
            <th style="color:#468847;background-color:rgb(223, 240, 216);"><h4><i class="fa fa-thumbs-up fa-hw"></i> Valid</h4></th>
            <th style="color:#B94A48;background-color:#F2DEDE;"><h4><i class="fa fa-thumbs-down fa-hw"></i> Invalid</h4></th>
            <th style="color:#3A87AD;background-color:#D9EDF7;"><h4><i class="fa fa-dot-circle-o fa-hw"></i> Efficiency</h4></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><h4>My Shares</h4></td>
            <td style="color:#468847;background-color:rgb(223, 240, 216);"><h4 id="b-yvalid">{$GLOBAL.userdata.shares.valid|number_format}</h4></td>
            <td style="color:#B94A48;background-color:#F2DEDE;"><h4 id="b-yivalid">{$GLOBAL.userdata.shares.invalid|number_format}</h4></td>
            <td style="color:#3A87AD;background-color:#D9EDF7;">
              <h4 id="b-yefficiency">{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</h4>
            </td>
          </tr>
          <tr>
            <td><h4>Pool Shares</h4></td>
            <th style="color:#468847;background-color:rgb(223, 240, 216);"><h4 id="b-pvalid">{$GLOBAL.roundshares.valid|number_format}</h4></td>
            <td style="color:#B94A48;background-color:#F2DEDE;"><h4 id="b-pivalid">{$GLOBAL.roundshares.invalid|number_format}</h4></td>
            <td style="color:#3A87AD;background-color:#D9EDF7;">
              <h4 id="b-pefficiency">{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}<h4>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds.
      </div>
    </div>
  </div>
