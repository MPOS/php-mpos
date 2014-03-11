  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-align-left fa-fw"></i> Round statistics
        </h4>
      </div>
      <div class="panel-footer">
        <div class="row text-center">
          <div class="col-xs-4 b-r">
            <i class="fa fa-th-large fa-2x"></i>
            <p id="b-nblock" class="h4 font-bold m-t">{$NETWORK.block}</p>
            <p class="text-muted">Current Block</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-bar-chart-o fa-flip-horizontal fa-2x"></i>
            <p id="b-roundprogress" class="h4 font-bold m-t">{$ESTIMATES.percent|number_format:"2"}%</p>
            <p class="text-muted">Of Expected Shares</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h4 font-bold m-t">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</p>
            <p class="text-muted">{$GLOBAL.config.currency} Estimated Earnings</p>
          </div>
        </div>
      </div>
      <table class="table borderless m-b-none text-small">
        <thead>
          <tr>
            <th><h4><i class="fa fa-cloud fa-fw"></i> Round Shares</h4></th>
            <th><h4><i class="fa fa-thumbs-up fa-hw"></i> Valid</h4></th>
            <th><h4><i class="fa fa-thumbs-down fa-hw"></i> Invalid</h4></th>
            <th><h4><i class="fa fa-dot-circle-o fa-hw"></i> Efficiency</h4></th>
          </tr>
          <tr>
            <th><h4><i class="fa fa-user fa-fw"></i> My Shares</h4></td>
            <th><h4 id="b-yvalid">{$GLOBAL.userdata.shares.valid|number_format}</h4></th>
            <th><h4 id="b-yivalid">{$GLOBAL.userdata.shares.invalid|number_format}</h4></th>
            <th>
              <h4 id="b-yefficiency">{if $GLOBAL.userdata.shares.valid > 0}{(100 - ($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}</h4>
            </th>
          </tr>
          <tr>
            <th><h4><i class="fa fa-users fa-fw"></i> Pool Shares</h4></th>
            <th><h4 id="b-pvalid">{$GLOBAL.roundshares.valid|number_format}</h4></th>
            <th><h4 id="b-pivalid">{$GLOBAL.roundshares.invalid|number_format}</h4></th>
            <th>
              <h4 id="b-pefficiency">{if $GLOBAL.roundshares.valid > 0}{(100 - ($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100))|number_format:"2"}%{else}0.00%{/if}<h4>
            </th>
          </tr>
        </thead>
      </table>
      <div class="panel-footer">
        <div class="row text-center">
          <div class="col-xs-4 b-r">
            <i class="fa fa-map-marker fa-2x"></i>
            <p id="b-diff" class="h4 font-bold m-t">{$NETWORK.difficulty|number_format:"8"}</p>
            <p class="text-muted">Difficulty</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-sitemap fa-2x"></i>
            <p id="b-nextdiff" class="h4 font-bold m-t">{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</p>
            <p class="text-muted">Est Next Difficulty</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-clock-o fa-2x"></i>
            <p id="b-esttimeperblock" class="h4 font-bold m-t">{$NETWORK.EstTimePerBlock|seconds_to_words}</p>
            <p class="text-muted">Est. Avg. Time per Block</p>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds.
      </div>
    </div>
  </div>
