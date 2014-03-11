      <div class="panel-footer">
        <div class="row text-center">
          <div class="col-xs-4 b-r">
            <i class="fa fa-th-large fa-2x"></i>
            <p id="b-nblock" class="h6 font-bold m-t">{$NETWORK.block}</p>
            <p class="h6 text-muted">Current Block</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-bar-chart-o fa-flip-horizontal fa-2x"></i>
            <p id="b-roundprogress" class="h6 font-bold m-t">{$ESTIMATES.percent|number_format:"2"}%</p>
            <p class="h6 text-muted">Of Expected Shares</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h6 font-bold m-t">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} Estimated Earnings</p>
          </div>
        </div>
        <div class="row text-center">
          <div class="col-xs-4 b-r">
            <i class="fa fa-map-marker fa-2x"></i>
            <p id="b-diff" class="h6 font-bold m-t">{$NETWORK.difficulty|number_format:"8"}</p>
            <p class="h6 text-muted">Difficulty</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-sitemap fa-2x"></i>
            <p id="b-nextdiff" class="h6 font-bold m-t">{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</p>
            <p class="h6 text-muted">Est Next Difficulty</p>
          </div>
          <div class="col-xs-4 b-r">
            <i class="fa fa-clock-o fa-2x"></i>
            <p id="b-esttimeperblock" class="h6 font-bold m-t">{$NETWORK.EstTimePerBlock|seconds_to_words}</p>
            <p class="h6 text-muted">Est. Avg. Time per Block</p>
          </div>
        </div>
      </div>
