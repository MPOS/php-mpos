{include file="global/block_header.tpl" BLOCK_HEADER="Transaction Log" BUTTONS=array(Confirmed,Unconfirmed)}
<div class="block_content tab_content" id="Confirmed" style="clear:;">
  <center>
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
        {if (
          ($TRANSACTIONS[transaction].type == 'Credit' and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations)
          or ($TRANSACTIONS[transaction].type == 'Donation' and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations)
          or $TRANSACTIONS[transaction].type == 'Debit_AP'
          or $TRANSACTIONS[transaction].type == 'Debit_MP'
        )}
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
    <p>
      <font color="" size="1">
        <b>Credit_AP</b> = Auto Threshold Payment, <b>Credit_MP</b> = Manual Payment, <b>Donation</b> = Donation, <b>Fee</b> = Pool Fees (if applicable)
      </font>
    </p>
  </center>
</div>
<div class="block_content tab_content" id="Unconfirmed" style="">
  <center>
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
        {if (
          $TRANSACTIONS[transaction].type == 'Credit' && $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations
          or ($TRANSACTIONS[transaction].type == 'Donation' and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations)
        )}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].sendAddress}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == Credit}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
          {if $TRANSACTIONS[transaction].type == Credit}
            {assign var="credits" value="`$credits+$TRANSACTIONS[transaction].amount`"}
          {else}
            {assign var="debits" value="`$debits+$TRANSACTIONS[transaction].amount`"}
          {/if}
        {/if}
{/section}
        <tr>
          <td colspan="5"><b>Unconfirmed Totals:</b></td>
          <td><b>{$credits - $debits}</b></td>
        </tr>
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your estimated rewards and donations/fees for all blocks awaiting {$GLOBAL.confirmations} confirmations.</font></p>
  </center>
</div>
{include file="global/block_footer.tpl"}
