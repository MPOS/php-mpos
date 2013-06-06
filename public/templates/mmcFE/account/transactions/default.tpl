{include file="global/block_header.tpl" BLOCK_HEADER="Transaction Log" BUTTONS=array(Confirmed,Unconfirmed,Orphan)}
<div class="block_content tab_content" id="Confirmed" style="clear:;">
  <center>
    {include file="global/pagination.tpl"}
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort">
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
        {if (
          (($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus')and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations)
          or ($TRANSACTIONS[transaction].type == 'Donation' and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations)
          or ($TRANSACTIONS[transaction].type == 'Fee' and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations)
          or $TRANSACTIONS[transaction].type == 'Credit_PPS'
          or $TRANSACTIONS[transaction].type == 'Fee_PPS'
          or $TRANSACTIONS[transaction].type == 'Donation_PPS'
          or $TRANSACTIONS[transaction].type == 'Debit_AP'
          or $TRANSACTIONS[transaction].type == 'Debit_MP'
        )}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
        {/if}
{/section}
      </tbody>
    </table>
    <p>
      <font color="" size="1">
        <b>Credit_AP</b> = Auto Threshold Payment, <b>Credit_MP</b> = Manual Payment, <b>Donation</b> = Donation, <b>Fee</b> = Pool Fees (if applicable)
      </font>
    </p>
  </center>
</div>
<div class="block_content tab_content" id="Unconfirmed" style="">
  <center>
    {include file="global/pagination.tpl" ID=2}
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort2">
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
        {if (
          ($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus') and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations
          or ($TRANSACTIONS[transaction].type == 'Donation' and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations)
          or ($TRANSACTIONS[transaction].type == 'Fee' and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations)
        )}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
          {if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}
            {assign var="credits" value="`$credits+$TRANSACTIONS[transaction].amount`"}
          {else}
            {assign var="debits" value="`$debits+$TRANSACTIONS[transaction].amount`"}
          {/if}
        {/if}
{/section}
        <tr>
          <td colspan="5"><b>Unconfirmed Totals:</b></td>
          <td><b>{$credits|default - $debits|default}</b></td>
        </tr>
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your estimated rewards and donations/fees for all blocks awaiting {$GLOBAL.confirmations} confirmations.</font></p>
  </center>
</div>
<div class="block_content tab_content" id="Orphan" style="">
  <center>
    {include file="global/pagination.tpl"}
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort3">
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
        {if (
          $TRANSACTIONS[transaction].type == 'Orphan_Credit'
          or $TRANSACTIONS[transaction].type == 'Orphan_Donation'
          or $TRANSACTIONS[transaction].type == 'Orphan_Fee'
          or $TRANSACTIONS[transaction].type == 'Orphan_Bonus'
        )}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Orphan_Credit' or $TRANSACTIONS[transaction].type == 'Orphan_Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
          {if $TRANSACTIONS[transaction].type == 'Orphan_Credit' or $TRANSACTIONS[transaction].type == 'Orphan_Bonus'}
            {assign var="orphan_credits" value="`$orphan_credits+$TRANSACTIONS[transaction].amount`"}
          {else}
            {assign var="orphan_debits" value="`$orphan_debits+$TRANSACTIONS[transaction].amount`"}
          {/if}
        {/if}
{/section}
        <tr>
          <td colspan="5"><b>Orphaned Totals:</b></td>
          <td><b>{$orphan_credits|default - $orphan_debits|default}</b></td>
        </tr>
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your orphaned transactions for blocks not part of the main blockchain.</font></p>
  </center>
</div>
{include file="global/block_footer.tpl"}
