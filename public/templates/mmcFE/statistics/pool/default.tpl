{include file="global/block_header.tpl" BLOCK_HEADER="Pool Statistics" BLOCK_STYLE="clear:none;"}

{include file="statistics/pool/contributors_shares.tpl"}

{include file="statistics/pool/contributors_hashrate.tpl"}

{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Server Stats" BLOCK_STYLE="clear:both;" STYLE="padding-left:5px;padding-right:5px;"}
<table class="" width="100%" style="font-size:13px;">
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td>{$GLOBAL.hashrate|number_format:"3"} {$GLOBAL.hashunits.pool}</td>
    </tr>
    <tr>
      <td class="leftheader">Pool Efficiency</td>
      <td>{if $GLOBAL.roundshares.valid > 0}{($GLOBAL.roundshares.valid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%{else}0%{/if}</td>
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
      <td>{if $GLOBAL.website.blockexplorer.url}<a href="{$GLOBAL.website.blockexplorer.url}{$LASTBLOCKHASH|default:""}" target="_new">{$LASTBLOCK|default:"0"}</a>{else}{$LASTBLOCK|default:"0"}{/if}</td>
    </tr>
    <tr>
      <td class="leftheader">Current Difficulty</td>
      {if ! $GLOBAL.website.chaininfo.disabled}
      <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2">{$DIFFICULTY}</font></a></td>
      {else}
      <td><font size="2">{$DIFFICULTY}</font></td>
      {/if}
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Time per Round</td>
      <td>{$ESTTIME|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Est. Shares this Round</td>
      {assign var=estshares value=(65536 * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16))}
      <td>{$estshares|number_format:"0"} <font size="1">(done: {(100 / $estshares * $GLOBAL.roundshares.valid)|number_format:"2"} %)</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td>{$TIMESINCELAST|seconds_to_words}</td>
    </tr>
  </tbody>
</table>
{if !$GLOBAL.website.api.disabled}<li>These stats are also available in JSON format <a href="{$smarty.server.PHP_SELF}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key}">HERE</a></li>{/if}
{include file="global/block_footer.tpl"}


{include file="statistics/blocks/small_table.tpl" ALIGN="right" SHORT=true}

{include file="global/block_footer.tpl"}
