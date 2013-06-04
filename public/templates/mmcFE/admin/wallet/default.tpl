{include file="global/block_header.tpl" BLOCK_HEADER="Wallet Information"}
<table width="350px">
  <tr>
    <th>Wallet Balance</th>
    <td class="right">{$BALANCE|number_format:"8"}</td>
  </tr>
  <tr>
    <th>Locked for users</th>
    <td class="right">{$LOCKED|number_format:"8"}</td>
  </tr>
  <tr>
    <th>Liquid Assets</th>
    <td class="right">{($BALANCE - $LOCKED)|number_format:"8"}</td>
  </tr>
</table>
{include file="global/block_footer.tpl"}
