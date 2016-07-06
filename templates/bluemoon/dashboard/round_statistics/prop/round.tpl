      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-4">
          <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">Current Block</div>
              <!--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>-->
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-th-large"></i></div>
              <div class="pull-right number"><p class="h5" id="b-nblock">{$NETWORK.block}</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">Better than last week</span>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4">
          <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">Difficulty</div>
              <!--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>-->
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-map-marker"></i></div>
              <div class="pull-right number"><p class="h5" id="b-diff">{$NETWORK.difficulty|number_format:"8"}</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">Better than last week</span>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4">
          <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">Est. Next Difficulty</div>
              <!--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>-->
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-sitemap"></i></div>
              <div class="pull-right number"><p class="h5" id="b-nextdiff">{if $GLOBAL.nethashrate > 0}{$NETWORK.EstNextDifficulty|number_format:"8"}{else}n/a{/if}</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">{if $GLOBAL.nethashrate > 0} change in {$NETWORK.BlocksUntilDiffChange} Blocks{else}No Estimates{/if}</span>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4">
          <div id="widgetblockpercent" class="mini-widget {if $ESTIMATES.percent|number_format:"2" <= 100}mini-widget-green{else}mini-widget-red{/if}">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">Of Expected Shares</div>
              <!--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>-->
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-bar-chart"></i></div>
              <div class="pull-right number"><p class="h5" id="b-roundprogress">{$ESTIMATES.percent|number_format:"2"} %</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">Difficulty{if $GLOBAL.nethashrate > 0} change in {$NETWORK.BlocksUntilDiffChange} Blocks{else}No Estimates{/if}</span>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4">
          <div id="widgetblocktime" class="mini-widget {if $NETWORK.EstTimePerBlock > $LASTBLOCKTIME}mini-widget-green{else}mini-widget-red{/if}">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">Est. Avg. Time per Block</div>
              <!--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>-->
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-clock-o"></i></div>
              <div class="pull-right number"><p class="h5" id="b-esttimeperblock">{$NETWORK.EstTimePerBlock|seconds_to_hhmmss}</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">Last Block found <span id="b-timesincelastblock">{$LASTBLOCKTIME|seconds_to_hhmmss}</span> ago</span>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4">
          <div class="mini-widget mini-widget-grey">
            <div class="mini-widget-heading clearfix">
              <div class="pull-left">{$GLOBAL.config.currency} Est. Earnings</div>
              {if $GLOBAL.fees > 0}
              <div class="pull-right">{if $GLOBAL.fees < 0.0001}{$GLOBAL.fees|escape|number_format:"8"}{else}{$GLOBAL.fees|escape}{/if}<sup>%</sup></div>
              {/if}
            </div>
            <div class="mini-widget-body clearfix">
              <div class="pull-left"><i class="fa fa-money"></i></div>
              <div class="pull-right number"><p class="h5" id="b-payout">{$GLOBAL.userdata.estimates.payout|number_format:$PRECISION}</p></div>
            </div>
            <div class="mini-widget-footer center-align-text">
              <span class="small">
              {if $GLOBAL.userdata.no_fees}
              No pool fee and
              {else if $GLOBAL.fees > 0}
              <font color="orange">{if $GLOBAL.fees < 0.0001}{$GLOBAL.fees|escape|number_format:"8"}{else}{$GLOBAL.fees|escape}{/if}%</font> pool fee and
              {else}
              No pool fee and
              {/if}
              {if $GLOBAL.userdata.donate_percent > 0}
              <font color="green">{$GLOBAL.userdata.donate_percent|escape}%</font> donation.
              {else}
              no <a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit">donation</a>.
              {/if}
              </span>
            </div>
          </div>
        </div>
      </div>