{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="Round Transactions"}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th scope="col">User Name</th>
        <th scope="col">Type</th>
        <th class="right" scope="col">Round %</th>
        <th class="right" scope="col">Amount</th>
      </tr>
    </thead>
    <tbody>
{section txs $ROUNDTRANSACTIONS}
      <tr{if $GLOBAL.userdata.username|default:"" == $ROUNDTRANSACTIONS[txs].username} style="background-color:#99EB99;"{else} class="{cycle values="odd,even"}"{/if}>
        <td>{if $ROUNDTRANSACTIONS[txs].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$ROUNDTRANSACTIONS[txs].username|escape}{/if}</td>
        <td class="right">{$ROUNDTRANSACTIONS[txs].type|default:""}</td>
        <td class="right">{(( 100 / $BLOCKDETAILS.shares) * $ROUNDSHARES[txs].valid)|number_format:"2"}</td>
        <td class="right">{$ROUNDTRANSACTIONS[txs].amount|default:"0"|number_format:"8"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

