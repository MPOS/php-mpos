{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Top Hashrate Contributers"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th align="left" scope="col">User Name</th>
        <th class="right" scope="col">{$GLOBAL.hashunits.personal}</th>
        <th class="right">{$GLOBAL.config.currency}/Day</th>
        {if $GLOBAL.config.price.currency}<th class="right">{$GLOBAL.config.price.currency}/Day</th>{/if}
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
      <tr{if $GLOBAL.userdata.username == $CONTRIBHASHES[contrib].account}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{$rank++}</td>
        <td>{if $CONTRIBHASHES[contrib].is_anonymous|default:"0" == 1}anonymous{else}{$CONTRIBHASHES[contrib].account|escape}{/if}</td>
        <td class="right">{($CONTRIBHASHES[contrib].hashrate * $GLOBAL.hashmods.personal)|number_format:"2"}</td>
        <td class="right">{$estday|number_format:"3"}</td>
        {if $GLOBAL.config.price.currency}<td class="right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>{/if}
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:""}
      {if $GLOBAL.userdata.hashrate > 0}{math assign="myestday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$GLOBAL.userdata.hashrate}{/if}
      <tr style="background-color:#99EB99;">
        <td>n/a</td>
        <td>{$GLOBAL.userdata.username|escape}</td>
        <td class="right">{$GLOBAL.userdata.hashrate}</td>
        <td class="right">{$myestday|number_format:"3"|default:"n/a"}</td>
        {if $GLOBAL.config.price.currency}<td class="right">{($myestday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>{/if}
      </tr>
{/if}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}
