 <article class="module width_half">
  <header><h3>General Statistics</h3></header>
  <div class="module_content">
    <table width="100%">
      <tbody>
        <tr>
          <th align="left" width="50%">Pool Hash Rate</th>
          <td width="70%"><span id="b-hashrate">{$GLOBAL.hashrate|number_format:"3"}</span> {$GLOBAL.hashunits.pool}</td>
        </tr>
        <tr>
          <th align="left">Pool Efficiency</td>
          <td>{if $GLOBAL.roundshares.valid > 0}{($GLOBAL.roundshares.valid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%{else}0%{/if}</td>
        </tr>
        <tr>
          <th align="left">Current Active Workers</td>
          <td id="b-workers">{$GLOBAL.workers}</td>
        </tr>
        <tr>
          <th align="left">Current Difficulty</td>
      {if ! $GLOBAL.website.chaininfo.disabled}
          <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2"><span id="b-diff">{$NETWORK.difficulty}</span></font></a></td>
      {else}
          <td><font size="2"><span id="b-diff">{$NETWORK.difficulty}</span></font></td>
      {/if}
        </tr>
        <tr>
          <th align="left">Est. Next Difficulty</td>
      {if ! $GLOBAL.website.chaininfo.disabled}
          <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2">{$NETWORK.EstNextDifficulty}  (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</font></a></td>
      {else}
          <td><font size="2">{$NETWORK.EstNextDifficulty} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</font></td>
      {/if}
        </tr>
        <tr>
          <th align="left">Est. Avg. Time per Round (Network)</td>
          <td><font size="2">{$NETWORK.EstTimePerBlock|seconds_to_words}</font></td>
        </tr>
        <tr>
          <th align="left">Est. Avg. Time per Round (Pool)</td>
          <td>{$ESTTIME|seconds_to_words}</td>
        </tr>
        <tr>
          <th align="left">Est. Shares this Round</td>
          <td id="b-target">{$ESTIMATES.shares} (done: {$ESTIMATES.percent}%)</td>
        </tr>
    {if ! $GLOBAL.website.blockexplorer.disabled}
        <tr>
          <th align="left" width="50%">Next Network Block</td>
          <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK})</a></font></td>
        </tr>
    {else}
        <tr>
          <th align="left">Next Network Block</td>
          <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp; (Current: {$CURRENTBLOCK})</td>
        </tr>
    {/if}
        <tr>
          <th align="left">Last Block Found</td>
          <td colspan="3"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round&height={$LASTBLOCK}" target="_new">{$LASTBLOCK|default:"0"}</a></td>
        </tr>
        <tr>
          <th align="left">Time Since Last Block</td>
          <td colspan="3">{$TIMESINCELAST|seconds_to_words}</td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>
  <footer>
{if !$GLOBAL.website.api.disabled}<ul><li>These stats are also available in JSON format <a href="{$smarty.server.PHP_SELF}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key|default:""}">HERE</a></li></ul>{/if}
  </footer>
</article>
