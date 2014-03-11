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
