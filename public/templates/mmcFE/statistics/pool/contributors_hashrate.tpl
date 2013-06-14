{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Top Hashrate Contributers"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th align="left" scope="col">User Name</th>
        <th class="right" scope="col">KH/s</th>
        <th class="right">{$GLOBAL.config.currency}/Day</th>
        <th class="right">USD/Day</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
      <tr{if $GLOBAL.userdata.username == $CONTRIBHASHES[contrib].account}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{$rank++}</td>
        <td>{$CONTRIBHASHES[contrib].account}</td>
        <td class="right">{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
        <td class="right">{$estday|number_format:"3"}</td>
        <td class="right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>
      </tr>
{/section}
{if $listed != 1}
      <tr style="background-color:#99EB99;">
        <td>n/a</td>
        <td>{$GLOBAL.userdata.username}</td>
        <td class="right">{$GLOBAL.userdata.hashrate}</td>
        <td class="right">{$estday|number_format:"3"|default:"n/a"}</td>
        <td class="right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>
      </tr>
{/if}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}
