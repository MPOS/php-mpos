<article class="module width_half" style="min-height: 350px">
  <header><h3>Contributor Hashrates</h3></header>
  <div>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Rank</th>
        <th align="right"></th>
        <th align="left" scope="col">User Name</th>
        <th align="right" scope="col">KH/s</th>
        <th align="right">{$GLOBAL.config.currency}/Day</th>
        {if $GLOBAL.config.price.currency}<th align="right" style="padding-right: 25px;">{$GLOBAL.config.price.currency}/Day</th>{/if}
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
      <tr{if $GLOBAL.userdata.username|default:"" == $CONTRIBHASHES[contrib].account}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td align="center">{$rank++}</td>
        <td align="right">{if $CONTRIBHASHES[contrib].donate_percent > 0}<i class="icon-star-empty"></i>{/if}</td>
        <td>{if $CONTRIBHASHES[contrib].is_anonymous|default:"0" == 1}anonymous{else}{$CONTRIBHASHES[contrib].account|escape}{/if}</td>
        <td align="right">{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
        <td align="right">{$estday|number_format:"3"}</td>
        {if $GLOBAL.config.price.currency}<td align="right" style="padding-right: 25px;">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>{/if}
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:""}
      {if $GLOBAL.userdata.hashrate > 0}{math assign="myestday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$GLOBAL.userdata.hashrate}{/if}
      <tr>
        <td align="center">n/a</td>
        <td align="right">{if $GLOBAL.userdata.donate_percent > 0}<i class="icon-star-empty"></i>{/if}</td>
        <td>{$GLOBAL.userdata.username|escape}</td>
        <td align="right">{$GLOBAL.userdata.hashrate}</td>
        <td align="right">{$myestday|number_format:"3"|default:"n/a"}</td>
        {if $GLOBAL.config.price.currency}<td align="right" style="padding-right: 25px;">{($myestday * $GLOBAL.price)|default:"n/a"|number_format:"2"}</td>{/if}
      </tr>
{/if}
    </tbody>
  </table>
</article>
