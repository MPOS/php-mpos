      <div class="panel-footer">
        <div class="row text-center">
          <div class="col-md-spark">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h5 font-bold m-t">{$GLOBAL.userdata.estimates.hours1|number_format:"12"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} 1 Hour Estimated Earnings</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h6 font-bold m-t">{$GLOBAL.userdata.estimates.hours24|number_format:"12"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} 24 Hours Estimated Earnings</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h5 font-bold m-t">{$GLOBAL.userdata.estimates.days7|number_format:"12"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} 7 Days Estimated Earnings</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h5 font-bold m-t">{$GLOBAL.userdata.estimates.days14|number_format:"12"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} 14 Days Estimated Earnings</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-money fa-2x"></i>
            <p id="b-payout" class="h5 font-bold m-t">{$GLOBAL.userdata.estimates.days30|number_format:"12"}</p>
            <p class="h6 text-muted">{$GLOBAL.config.currency} 30 Days Estimated Earnings</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-th-large fa-2x"></i>
            <p id="b-ppsvalue" class="h5 font-bold m-t">{$GLOBAL.ppsvalue}</p>
            <p class="h6 text-muted">PPS Value</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-bar-chart-o fa-flip-horizontal fa-2x"></i>
            <p id="b-unpaidshares" class="h6 font-bold m-t">{$GLOBAL.userdata.pps.unpaidshares}</p>
            <p class="h6 text-muted">Unpaid Shares</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-map-marker fa-2x"></i>
            <p id="b-diff" class="h5 font-bold m-t">{$NETWORK.difficulty|number_format:"8"}</p>
            <p class="h6 text-muted">Difficulty</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-sitemap fa-2x"></i>
            <p id="b-nextdiff" class="h5 font-bold m-t">{if $GLOBAL.nethashrate > 0}{$NETWORK.EstNextDifficulty|number_format:"8"}{else}n/a{/if}</p>
            <p id="b-nextdiffc" class="h6 font-bold m-t">{if $GLOBAL.nethashrate > 0}Change in {$NETWORK.BlocksUntilDiffChange} Blocks{else}No Estimates{/if}</p>
            <p class="h6 text-muted">Est Next Difficulty</p>
          </div>
          <div class="col-md-spark">
            <i class="fa fa-clock-o fa-2x"></i>
            <p id="b-esttimeperblock" class="h5 font-bold m-t">{$NETWORK.EstTimePerBlock|seconds_to_hhmmss}</p>
            <p class="h6 text-muted">Est. Avg. Time per Block</p>
          </div>
        </div>
      </div>
