{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="Round Transactions"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Tx Id</th>
        <th scope="col">User Name</th>
        <th scope="col">Type</th>
        <th class="right" scope="col">Amount</th>
      </tr>
    </thead>
    <tbody>
{section txs $ROUNDTRANSACTIONS}
      <tr class="{cycle values="odd,even"}">
        <td>{$ROUNDTRANSACTIONS[txs].id|default:"0"}</td>
        <td>{$ROUNDTRANSACTIONS[txs].username|escape}</td>
        <td class="right">{$ROUNDTRANSACTIONS[txs].type|default:""}</td>
        <td class="right">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}
