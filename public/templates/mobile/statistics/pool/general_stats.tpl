<table>
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td colspan="4">{($GLOBAL.hashrate)|number_format:"3"} {$GLOBAL.hashunits.pool}</td>
    </tr>
    <tr>
      <td class="leftheader">Pool Efficiency</td>
      <td colspan="4">{if $GLOBAL.roundshares.valid > 0}{(100 - (100 / $GLOBAL.roundshares.valid * $GLOBAL.roundshares.invalid))|number_format:"2"}{else}0{/if} %</td>
    </tr>
    <tr>
      <td class="leftheader">Current Active Workers</td>
      <td colspan="4">{$GLOBAL.workers}</td>
    </tr>
    {if ! $GLOBAL.website.blockexplorer.disabled}
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td colspan="4">{$CURRENTBLOCK + 1} &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK})</a></font></td>
    </tr>
    {else}
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td colspan="4">{$CURRENTBLOCK + 1} &nbsp;&nbsp; (Current: {$CURRENTBLOCK})</td>
    </tr>
    {/if}
    <tr>
      <td class="leftheader">Last Block Found</td>
      <td colspan="4">{if $GLOBAL.website.blockexplorer.url}<a href="{$GLOBAL.website.blockexplorer.url}{$LASTBLOCKHASH}" target="_new">{$LASTBLOCK|default:"0"}</a>{else}{$LASTBLOCK|default:"0"}{/if}</td>
    </tr>
    {if ! $GLOBAL.website.chaininfo.disabled}
    <tr>
      <td class="leftheader">Current Difficulty</td>
      <td colspan="4"><a href="{$GLOBAL.website.chaininfo.url}" target="_new">{$DIFFICULTY}</a></td>
    </tr>
    <tr>
      <td class="leftheader">Est. Next Difficulty</td>
      <td colspan="4"><a href="{$GLOBAL.website.chaininfo.url}" target="_new">{$NETWORK.EstNextDifficulty}</a></td>
    </tr>
    {/if}
    <tr>
      <td class="leftheader">Est. Avg. Time per Round (Network)</td>
      <td colspan="4">{$NETWORK.EstTimePerBlock|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Time per Round (Pool)</td>
      <td colspan="4">{$ESTTIME|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Est. Shares this Round</td>
      <td colspan="4">{((65536 * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16)))|number_format:"0"} <font size="1">(done: {(100 / ((65536 * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16))) * $GLOBAL.roundshares.valid)|number_format:"2"} %)</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td colspan="4">{$TIMESINCELAST|seconds_to_words}</td>
    </tr>
  </tbody>
</table>
