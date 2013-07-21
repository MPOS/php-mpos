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
{assign var=has_confirmed value=false}
{section transaction $TRANSACTIONS}
        {if (
          ( ( $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Fee' ) and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations )
          or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Fee_PPS' or $TRANSACTIONS[transaction].type == 'Donation_PPS'
          or $TRANSACTIONS[transaction].type == 'Debit_AP' or $TRANSACTIONS[transaction].type == 'Debit_MP' or $TRANSACTIONS[transaction].type == 'TXFee'
        )}
        {assign var=has_credits value=true}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
        {/if}
{/section}
{if !$has_confirmed}
        <tr><td class="center" colspan="6"><b><i>No data</i></b></td></tr>
{/if}
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
{assign var=has_unconfirmed value=false}
{section transaction $TRANSACTIONS}
        {if
          (($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Fee') and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations)
        }
        {assign var=has_unconfirmed value=true}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
          {if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}
            {assign var="credits" value="`$credits|default:"0"+$TRANSACTIONS[transaction].amount`"}
          {else}
            {assign var="debits" value="`$debits|default:"0"+$TRANSACTIONS[transaction].amount`"}
          {/if}
        {/if}
{/section}
{if !$has_unconfirmed}
        <tr><td class="center" colspan="6"><b><i>No data</i></b></td></tr>
{/if}
        <tr>
          <td colspan="5"><b>Unconfirmed Totals:</b></td>
          <td><b>{($credits|default - $debits|default)|number_format:"8"}</b></td>
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
{assign var=has_orphaned value=false}
{section transaction $TRANSACTIONS}
        {if ($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Fee' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Bonus') and $TRANSACTIONS[transaction].confirmations == -1}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
          {if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}
            {assign var="orphan_credits" value="`$orphan_credits|default:"0"+$TRANSACTIONS[transaction].amount`"}
          {else}
            {assign var="orphan_debits" value="`$orphan_debits|default:"0"+$TRANSACTIONS[transaction].amount`"}
          {/if}
        {/if}
{/section}
{if !$has_orphaned}
        <tr><td class="center" colspan="6"><b><i>No data</i></b></td></tr>
{/if}
        <tr>
          <td colspan="5"><b>Orphaned Totals:</b></td>
          <td><b>{($orphan_credits|default - $orphan_debits|default)|number_format:"8"}</b></td>
        </tr>
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your orphaned transactions for blocks not part of the main blockchain.</font></p>
  </center>
</div>
{include file="global/block_footer.tpl"}
