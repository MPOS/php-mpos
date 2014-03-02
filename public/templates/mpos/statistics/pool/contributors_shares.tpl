<article class="module width_half">
  <header><h3>Contributor Shares</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Rank</th>
        <th align="center">Donor</th>
        <th align="center">User Name</th>
        <th align="right" style="padding-right: 30px;">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
      <tr{if $GLOBAL.userdata.username|default:""|lower == $CONTRIBSHARES[shares].account|lower}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td align="center">{$rank++}</td>
        <td align="center">{if $CONTRIBSHARES[shares].donate_percent|default:"0" >= 2}<i class="icon-award">{else if $CONTRIBSHARES[shares].donate_percent|default:"0" < 2 AND $CONTRIBSHARES[shares].donate_percent|default:"0" > 0}<i class="icon-star-empty">{else}<i class="icon-block"></i>{/if}</td>
        <td align="center">{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
        <td align="right" style="padding-right: 30px;">{$CONTRIBSHARES[shares].shares|number_format}</td>
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.shares.valid|default:"0" > 0}
      <tr>
        <td align="center">n/a</td>
        <td align="center">{if $GLOBAL.userdata.donate_percent|default:"0" >= 2}<i class="icon-star-empty"></i>{elseif $GLOBAL.userdata.donate_percent|default:"0" < 2 AND $GLOBAL.userdata.donate_percent|default:"0" > 0}<i class="icon-award"></i>{else}<i class="icon-block"></i>{/if}</td>
        <td align="center">{$GLOBAL.userdata.username|escape}</td>
        <td align="right" style="padding-right: 30px;">{$GLOBAL.userdata.shares.valid|number_format}</td>
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
