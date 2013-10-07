<article class="module width_half" style="min-height: 350px;">
  <header><h3>Contributor Shares</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Rank</th>
        <th align="right"></th>
        <th>User Name</th>
        <th align="right" style="padding-right: 25px;">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
      <tr{if $GLOBAL.userdata.username|default:"" == $CONTRIBSHARES[shares].account}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td align="center">{$rank++}</td>
        <td align="right">{if $CONTRIBSHARES[shares].donate_percent > 0}<i class="icon-star-empty"></i>{/if}</td>
        <td>{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
        <td align="right" style="padding-right: 25px;">{$CONTRIBSHARES[shares].shares|number_format}</td>
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:""}
      <tr>
        <td align="center">n/a</td>
        <td align="right">{if $GLOBAL.userdata.donate_percent > 0}<i class="icon-star-empty"></i>{/if}</td>
        <td>{if $GLOBAL.userdata.username > 0}<i class="icon-star-empty"></i>{/if}</td>
        <td align="right" style="padding-right: 25px;">{$GLOBAL.userdata.shares.valid|number_format}</td>
      </tr>
{/if}
    </tbody>
  </table>
</article>
