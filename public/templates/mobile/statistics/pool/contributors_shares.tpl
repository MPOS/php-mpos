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
{section hashrate $CONTRIBSHARES}
{if $GLOBAL.userdata.username == $CONTRIBSHARES[hashrate].account}{assign var=listed value=1}{/if}
      <tr>
        <th aign="center">{$rank++}</th>
        <td>{$CONTRIBSHARES[hashrate].account}</td>
        <td align="right">{$CONTRIBSHARES[hashrate].shares|number_format}</td>
      </tr>
{/section}
{if $listed != 1}
      <tr>
        <th>n/a</th>
        <td>{$GLOBAL.userdata.username}</td>
        <td align="right">{$GLOBAL.userdata.shares.valid|number_format}</td>
      </tr>
{/if}
    </tbody>
  </table>
