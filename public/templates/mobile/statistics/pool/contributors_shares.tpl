  <table data-role="table" id="shares" data-mode="columntoggle">
    <thead>
      <tr>
        <th>Rank</th>
        <th>User Name</th>
        <th align="right">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section shares $CONTRIBSHARES}
{if $GLOBAL.userdata.username|default:"" == $CONTRIBSHARES[shares].account}{assign var=listed value=1}{/if}
      <tr>
        <th aign="center">{$rank++}</th>
        <td>{if $CONTRIBSHARES[shares].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBSHARES[shares].account|escape}{/if}</td>
        <td align="right">{$CONTRIBSHARES[shares].shares|number_format}</td>
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.shares|default:"0" > 0}
      <tr>
        <th>n/a</th>
        <td>{$GLOBAL.userdata.username}</td>
        <td align="right">{$GLOBAL.userdata.shares.valid|number_format}</td>
      </tr>
{/if}
    </tbody>
  </table>
