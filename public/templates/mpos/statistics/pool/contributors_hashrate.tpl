<article class="module width_half">
  <header><h3>Contributor Hashrates</h3></header>
  <div>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Rank</th>
        <th align="center">Donor</th>
        <th align="center" scope="col">User Name</th>
        <th align="right" style="padding-right: 7px;" scope="col">KH/s</th>
        <th align="right">{$GLOBAL.config.currency}/Day</th>
        {if $GLOBAL.config.price.currency}<th align="right" style="padding-right: 25px;">{$GLOBAL.config.price.currency}/Day</th>{/if}
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
      <tr{if $GLOBAL.userdata.username|default:""|lower == $CONTRIBHASHES[contrib].account|lower}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td align="center">{$rank++}</td>
        <td align="center">{if $CONTRIBHASHES[contrib].donate_percent|default:"0" >= 2}<i class="icon-award">{elseif $CONTRIBHASHES[contrib].donate_percent|default:"0" < 2 AND $CONTRIBHASHES[contrib].donate_percent|default:"0" > 0}<i class="icon-star-empty">{else}<i class="icon-block"></i>{/if}</td>
        <td align="center" style="padding-right: 0px;">{if $CONTRIBHASHES[contrib].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBHASHES[contrib].account|escape}{/if}</td>
        <td align="right" style="padding-right: 0px;">{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
        <td align="right" style="padding-right: 0px;">{$estday|number_format:"3"}</td>
        {if $GLOBAL.config.price.currency}<td align="right" style="padding-right: 30px;">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>{/if}
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.rawhashrate|default:"0" > 0}
      {math assign="myestday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$GLOBAL.userdata.rawhashrate}
      <tr>
        <td align="center">n/a</td>
        <td align="center">{if $GLOBAL.userdata.donate_percent|default:"0" >= 2}<i class="icon-star-empty"></i>{elseif $GLOBAL.userdata.donate_percent|default:"0" < 2 AND $GLOBAL.userdata.donate_percent|default:"0" > 0}<i class="icon-award"></i>{else}<i class="icon-block"></i>{/if}</td>
        <td align="center" style="padding-right: 0px;">{$GLOBAL.userdata.username|escape}</td>
        <td align="right" style="padding-right: 0px;">{$GLOBAL.userdata.rawhashrate|number_format}</td>
        <td align="right" style="padding-right: 0px;">{$myestday|number_format:"3"|default:"n/a"}</td>
        {if $GLOBAL.config.price.currency}<td align="right" style="padding-right: 30px;">{($myestday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>{/if}
      </tr>
{/if}
    </tbody>
  </table>
  <footer>
    <ul>
      <i class="icon-block"> no Donation </i>
      <i class="icon-star-empty"> 0&#37;&#45;2&#37; Donation </i>
      <i class="icon-award"> 2&#37; or more Donation </i>
    </ul>
  </footer>
</article>
