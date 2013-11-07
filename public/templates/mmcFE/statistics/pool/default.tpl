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
    {if !$GLOBAL.website.blockexplorer.disabled}
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
      <td>{if !$GLOBAL.website.blockexplorer.disabled}<a href="{$GLOBAL.website.blockexplorer.url}{$LASTBLOCKHASH|default:""}" target="_new">{$LASTBLOCK|default:"0"}</a>{else}{$LASTBLOCK|default:"0"}{/if}</td>
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
      {assign var=estshares value=(pow(2, (32 - $GLOBAL.config.target_bits)) * $DIFFICULTY) / pow(2, ($GLOBAL.config.targetdiff - 16))}
      <td>{$estshares|number_format:"0"} <font size="1">(done: {(100 / $estshares * $GLOBAL.roundshares.valid)|number_format:"2"} %)</td>
    </tr>

        <tr>
          <th align="left"></th>
          <th align="center">Est. Blocks</th>
          <th align="center">Blocks found</th>
          <th align="center">Luck</th>
        </tr>
        <tr>
          <th align="left">since 1st Block</td>
          <td align="center">{($FIRSTBLOCKFOUND / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.Total}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / ($FIRSTBLOCKFOUND / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / ($FIRSTBLOCKFOUND / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last hour</td>
          <td align="center">{(3600 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.1HourTotal / (3600 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.1HourTotal / (3600 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last day</td>
          <td align="center">{(86400 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (86400 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (86400 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last week</td>
          <td align="center">{(604800 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (604800 / $ESTTIME) * 100)|number_format:"2"} %{else} {($LASTBLOCKSBYTIME.Total / (604800 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last month</td>
          <td align="center">{(419200 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (2419200 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (2419200 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last year</td>
          <td align="center">{(29030400 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (29030400 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (29030400 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
    </table>
{if !$GLOBAL.website.api.disabled}<li>These stats are also available in JSON format <a href="{$smarty.server.PHP_SELF}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key}">HERE</a></li>{/if}
{include file="global/block_footer.tpl"}


{include file="statistics/blocks/small_table.tpl" ALIGN="right" SHORT=true}

{include file="global/block_footer.tpl"}
