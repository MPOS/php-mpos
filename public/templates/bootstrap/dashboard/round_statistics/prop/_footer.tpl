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
