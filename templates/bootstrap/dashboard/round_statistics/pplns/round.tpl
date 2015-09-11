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
                  <p class="h6">{t}Current Block{/t}</p>
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
                  <p class="h6">{t}Of Expected Shares{/t}</p>
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
                  <p class="h5 up-more" id="b-payout">{$GLOBAL.userdata.estimates.payout|number_format:$PRECISION}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">{$GLOBAL.config.currency} {t}Est. Earnings{/t}</p>
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
                  <p class="h6">{t}Difficulty{/t}</p>
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
                  <p class="h5" id="b-nextdiff">{if $GLOBAL.nethashrate > 0}{$NETWORK.EstNextDifficulty|number_format:"8"}{else}n/a{/if}</p>
                </div>
                <div class="circle-tile-number text-faded">
                  <p class="h6">{t}Est Next Difficulty{/t}{if $GLOBAL.nethashrate > 0}<br/>{t 1=$NETWORK.BlocksUntilDiffChange}Change in %1 Blocks{/t}{else}{t}No Estimates{/t}{/if}</p>
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
                  <p class="h6">{t}Est. Avg. Time per Block{/t}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
