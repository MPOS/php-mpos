<article class="module width_quarter">
  <header><h3>Wallet Information</h3></header>
  <table width="25%" class="tablesorter">
  <tr>
    <td align="left">Wallet Balance</td>
    <td align="left">{$BALANCE|number_format:"8"}</td>
  </tr>
  <tr>
    <td align="left">Locked for users</td>
    <td align="left">{$LOCKED|number_format:"8"}</td>
  </tr>
  <tr>
    <td align="left">Unconfirmed</td>
    <td align="left">{$UNCONFIRMED|number_format:"8"}</td>
  </tr>
  <tr>
    <td align="left">Liquid Assets</td>
    <td align="left">{($BALANCE - $LOCKED)|number_format:"8"}</td>
  </tr>
{if $NEWMINT >= 0}
  <tr>
    <td align="left">PoS New Mint</td>
    <td align="left">{$NEWMINT|number_format:"8"}</td>
  </tr>
{/if}
</table>
</article>
