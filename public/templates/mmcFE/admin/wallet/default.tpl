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
    <th>Unconfirmed</th>
    <td class="right">{$UNCONFIRMED|number_format:"8"}</td>
  </tr>
  <tr>
    <th>Liquid Assets</th>
    <td class="right">{($BALANCE - $LOCKED)|number_format:"8"}</td>
  </tr>
{if $NEWMINT >= 0}
  <tr>
    <th>PoS New Mint</th>
    <td class="right">{$NEWMINT|number_format:"8"}</td>
  </tr>
{/if}
  {if $COLDCOINS != 0}
  <tr>
    <th>Cold Coins</th>
    <td class="right">{$COLDCOINS|number_format:"8"}</td>
  </tr>
  {/if}
</table>
{include file="global/block_footer.tpl"}
