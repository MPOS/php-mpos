<div data-role="collapsible">
  <h3>User Hashrates</h3>
{include file="statistics/pool/contributors_hashrate.tpl"}
</div>

<div data-role="collapsible">
  <h3>User Shares</h3>
{include file="statistics/pool/contributors_shares.tpl"}
</div>

<div data-role="collapsible">
  <h3>General Stats</h3>
<table>
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td>{($GLOBAL.hashrate / 1000)|number_format:"3"} Mhash/s</td>
    </tr>
    <tr>
      <td class="leftheader">Pool Efficiency</td>
      <td>{if $GLOBAL.roundshares.valid > 0}{(100 - (100 / $GLOBAL.roundshares.valid * $GLOBAL.roundshares.invalid))|number_format:"2"}{else}0{/if} %</td>
    </tr>
    <tr>
      <td class="leftheader">Current Active Workers</td>
      <td>{$GLOBAL.workers}</td>
    </tr>
    {if $GLOBAL.website.blockexplorer.url}
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td>{$CURRENTBLOCK + 1} &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK})</a></font></td>
    </tr>
    {else}
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td>{$CURRENTBLOCK + 1} &nbsp;&nbsp; (Current: {$CURRENTBLOCK})</td>
    </tr>
    {/if}
    <tr>
      <td class="leftheader">Last Block Found</td>
      <td>{if $GLOBAL.website.blockexplorer.url}<a href="{$GLOBAL.website.blockexplorer.url}{$LASTBLOCKHASH}" target="_new">{$LASTBLOCK|default:"0"}</a>{else}{$LASTBLOCK|default:"0"}{/if}</td>
    </tr>
    {if ! $GLOBAL.website.chaininfo.disabled}
    <tr>
      <td class="leftheader">Current Difficulty</td>
      <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2">{$DIFFICULTY}</font></a></td>
    </tr>
    {/if}
    <tr>
      <td class="leftheader">Est. Avg. Time per Round</td>
      <td>{$ESTTIME|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Est. Shares this Round</td>
      <td>{((65536 * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16)))|number_format:"0"} <font size="1">(done: {(100 / ((65536 * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16))) * $GLOBAL.roundshares.valid)|number_format:"2"} %)</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td>{$TIMESINCELAST|seconds_to_words}</td>
    </tr>
  </tbody>
</table>
</div>

<div data-role="collapsible">
<h3>Last Blocks</h3>
{include file="statistics/blocks/small_table.tpl" ALIGN="right" SHORT=true}
</div>
