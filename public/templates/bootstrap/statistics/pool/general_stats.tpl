  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-bar-chart-o fa-fw"></i> General Statistics
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <tbody>
            <tr>
              <th width="50%">Pool Hash Rate</th>
              <td width="70%"><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</td>
            </tr>
            <tr>
              <th>Pool Efficiency</td>
              <td>{if $GLOBAL.roundshares.valid > 0}{($GLOBAL.roundshares.valid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%{else}0%{/if}</td>
            </tr>
            <tr>
              <th>Current Active Workers</td>
              <td id="b-workers">{$GLOBAL.workers}</td>
            </tr>
            <tr>
              <th>Current Difficulty</td>
              {if ! $GLOBAL.website.chaininfo.disabled}
              <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2"><span id="b-diff">{$NETWORK.difficulty}</span></font></a></td>
              {else}
              <td><span id="b-diff">{$NETWORK.difficulty}</span></td>
              {/if}
            </tr>
            <tr>
              <th>Est. Next Difficulty</td>
              {if ! $GLOBAL.website.chaininfo.disabled}
              <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2">{$NETWORK.EstNextDifficulty}  (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</font></a></td>
              {else}
              <td><font size="2">{$NETWORK.EstNextDifficulty} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</font></td>
              {/if}
            </tr>
            <tr>
              <th>Est. Avg. Time per Round (Network)</td>
              <td><font size="2">{$NETWORK.EstTimePerBlock|seconds_to_words}</font></td>
            </tr>
            <tr>
              <th>Est. Avg. Time per Round (Pool)</td>
              <td>{$ESTTIME|seconds_to_words}</td>
            </tr>
            <tr>
              <th>Est. Shares this Round</td>
              <td id="b-target">{$ESTIMATES.shares} (done: {$ESTIMATES.percent}%)</td>
            </tr>
            {if ! $GLOBAL.website.blockexplorer.disabled}
            <tr>
              <th width="50%">Next Network Block</td>
              <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK})</a></font></td>
            </tr>
            {else}
            <tr>
              <th>Next Network Block</td>
              <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp; (Current: {$CURRENTBLOCK})</td>
            </tr>
            {/if}
            <tr>
              <th>Last Block Found</td>
              <td colspan="3"><a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=round&height={$LASTBLOCK}" target="_new">{$LASTBLOCK|default:"0"}</a></td>
            </tr>
            <tr>
              <th>Time Since Last Block</td>
              <td colspan="3">{$TIMESINCELAST|seconds_to_words}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        {if !$GLOBAL.website.api.disabled}These stats are also available in JSON format <a href="{$smarty.server.SCRIPT_NAME}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key|default:""}">HERE</a>{/if}
      </div>
    </div>
  </div>