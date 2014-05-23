      <div class="panel-footer">
        <div class="row">
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-th-large fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up-more" id="b-nblock">{$NETWORK.block}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">Current Block</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-bar-chart-o fa-flip-horizontal fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up-more" id="b-roundprogress">{$ESTIMATES.percent|number_format:"2"}%</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">Of Expected Shares</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-money fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up-more" id="b-payout">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">{$GLOBAL.config.currency} Est Earnings</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-map-marker fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up-more" id="b-diff">{$NETWORK.difficulty|number_format:"8"}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">Difficulty</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-sitemap fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up" id="b-nextdiff">{if $GLOBAL.nethashrate > 0}{$NETWORK.EstNextDifficulty|number_format:"8"}{else}n/a{/if}</p>
                  <p class="h6" id="b-nextdiffc">{if $GLOBAL.nethashrate > 0}Change in {$NETWORK.BlocksUntilDiffChange} Blocks{else}No Estimates{/if}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6 up-more2">Est Next Difficulty</p>
                </div>
              </div>
            </div> 
         </div>
          <div class="col-lg-2 col-sm-6">
            <div class="circle-tile fade">
              <div class="circle-tile-heading lightblue">
                <i class="fa fa-clock-o fa-fw fa-2x"></i>
              </div>
              <div class="circle-tile-content lightblue">
                <div class="circle-tile-description text-faded">
                  <p class="h5 up-more" id="b-esttimeperblock">{$NETWORK.EstTimePerBlock|seconds_to_hhmmss}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">Est. Avg. Time per Block</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
