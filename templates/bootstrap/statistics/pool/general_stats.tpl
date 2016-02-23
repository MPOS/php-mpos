  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-bar-chart-o fa-fw"></i> {t}General Statistics{/t}
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tbody>
            <tr>
              <th width="50%">{t}Pool Hash Rate{/t}</th>
              <td width="70%"><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</td>
            </tr>
            <tr>
              <th>{t}Pool Efficiency{/t}</th>
              <td>{if $GLOBAL.roundshares.valid > 0}{($GLOBAL.roundshares.valid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%{else}0%{/if}</td>
            </tr>
            <tr>
              <th>{t}Current Active Workers{/t}</th>
              <td id="b-workers">{$GLOBAL.workers|number_format}</td>
            </tr>
            <tr>
              <th>{t}Current Difficulty{/t}</th>
              {if ! $GLOBAL.website.chaininfo.disabled}
              <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><span id="b-diff">{$NETWORK.difficulty|number_format:"8"}</span></a></td>
              {else}
              <td><span id="b-diff">{$NETWORK.difficulty|number_format:"8"}</span></td>
              {/if}
            </tr>
            <tr>
              <th>{t}Est. Next Difficulty{/t}</th>
              {if ! $GLOBAL.website.chaininfo.disabled}
              <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new">{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</a></td>
              {else}
              <td>{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</td>
              {/if}
            </tr>
            <tr>
              <th>{t}Est. Avg. Time per Round (Network){/t}</th>
              <td>{$NETWORK.EstTimePerBlock|seconds_to_words}</td>
            </tr>
            <tr>
              <th>{t}Est. Avg. Time per Round (Pool){/t}</th>
              <td>{$ESTTIME|seconds_to_words}</td>
            </tr>
            <tr>
              <th>{t}Est. Shares this Round{/t}</th>
              <td id="b-target">{$ESTIMATES.shares|number_format} (done: {$ESTIMATES.percent}%)</td>
            </tr>
            {if ! $GLOBAL.website.blockexplorer.disabled}
            <tr>
              <th width="50%">{t}Next Network Block{/t}</th>
              <td colspan="3">{($CURRENTBLOCK + 1)|number_format} &nbsp;&nbsp; (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK|number_format})</a></td>
            </tr>
            {else}
            <tr>
              <th>{t}Next Network Block{/t}</th>
              <td colspan="3">{($CURRENTBLOCK + 1)|number_format} &nbsp;&nbsp; (Current: {$CURRENTBLOCK|number_format})</td>
            </tr>
            {/if}
            <tr>
              <th>{t}Last Block Found{/t}</th>
              <td colspan="3"><a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=round&height={$LASTBLOCK}" target="_new">{$LASTBLOCK|default:"0"|number_format}</a></td>
            </tr>
            <tr>
              <th>{t}Time Since Last Block{/t}</th>
              <td colspan="3">{$TIMESINCELAST|seconds_to_words}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        <h6>{if !$GLOBAL.website.api.disabled}{t}These stats are also available in JSON format{/t} <a href="{$smarty.server.SCRIPT_NAME}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key|default:""}">{t}HERE{/t}</a>{/if}</h6>
      </div>
    </div>
  </div>
