<article class="module width_half">
  <header><h3>Round Shares</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Rank</th>
        <th align="left">User Name</th>
        <th align="right">Valid</th>
        <th align="right">Invalid</th>
        <th align="right" style="padding-right: 25px;">Invalid %</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $ROUNDSHARES}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDSHARES[contrib].username}{assign var=listed value=1} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td align="center">{$rank++}</td>
        <td>{if $ROUNDSHARES[contrib].is_anonymous|default:"0" == 1}anonymous{else}{$ROUNDSHARES[contrib].username|escape}{/if}</td>
        <td align="right">{$ROUNDSHARES[contrib].valid|number_format}</td>
        <td align="right">{$ROUNDSHARES[contrib].invalid|number_format}</td>
      	<td align="right" style="padding-right: 25px;">{($ROUNDSHARES[contrib].invalid / $ROUNDSHARES[contrib].valid * 100)|number_format:"2"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</article>
