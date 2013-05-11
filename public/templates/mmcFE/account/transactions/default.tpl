{include file="global/block_header.tpl" BLOCK_HEADER="Transaction Log" BUTTONS=array(Confirmed,Unconfirmed)}
<div class="block_content tab_content" id="Confirmed" style="clear:;">
  <center>
    <p>
      <font color="" size="1">
        <b>ATP</b> = Auto Threshold Payment, <b>MP</b> = Manual Payment, <b>Don_Fee</b> = donation amount + pool fees (if applicable)
      </font>
    </p>
    <table cellpadding="1" cellspacing="1" width="98%" class="sortable">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
{section transaction $TRANSACTIONS}
        {if (($TRANSACTIONS[transaction].type == 'Credit' and $TRANSACTIONS[transaction].confirmations >= 120) or $TRANSACTIONS[transaction].type != 'Credit')}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].sendAddress}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == Credit}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
        {/if}
{/section}
      </tbody>
    </table>
  </center>
</div>
<div class="block_content tab_content" id="Unconfirmed" style="">
  <center>
    <p><font color="" sizeze="1">Listed below are your estimated rewards and donations/fees for all blocks awaiting 120 confirmations.</font></p>
    <table cellpadding="1" cellspacing="1" width="98%" class="sortable">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
{section transaction $TRANSACTIONS}
        {if $TRANSACTIONS[transaction].type == 'Credit' && $TRANSACTIONS[transaction].confirmations < 120}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].sendAddress}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == Credit}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
        {assign var="sum" value="`$sum+$TRANSACTIONS[transaction].amount`"}
        {/if}
{/section}
        <tr>
          <td colspan="5"><b>Unconfirmed Totals:</b></td>
          <td><b>{$sum}</b></td>
        </tr>
      </tbody>
    </table>
  </center>
</div>
{include file="global/block_footer.tpl"}
