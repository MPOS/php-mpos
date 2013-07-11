{include file="global/block_header.tpl" BLOCK_HEADER="Transaction Log" BUTTONS=array(Confirmed,Unconfirmed,Orphan)}
  <center>
    <a href="{$smarty.server.PHP_SELF}?page=admin&action=transactions&start={$smarty.request.start|default:"0" - 30}"><img src="{$PATH}/images/prev.png" /></a>
    <a href="{$smarty.server.PHP_SELF}?page=admin&action=transactions&start={$smarty.request.start|default:"0" + 30}"><img src="{$PATH}/images/next.png" /></a>
  </center>
<div class="block_content tab_content" id="Confirmed" style="clear:;">
  <center>
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Account</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
      {assign var=confirmed value=0}
{section transaction $TRANSACTIONS}
        {if (
          ( ( $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Fee' ) and $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations )
          or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Fee_PPS' or $TRANSACTIONS[transaction].type == 'Donation_PPS'
          or $TRANSACTIONS[transaction].type == 'Debit_AP' or $TRANSACTIONS[transaction].type == 'Debit_MP' or $TRANSACTIONS[transaction].type == 'TXFee'
        )}
        {assign var=confirmed value=1}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].username}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
        {/if}
{/section}
        {if $confirmed != 1}
        <tr>
          <td class="center" colspan="7">No confirmed transactions</td>
        </tr>
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
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort2">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Account</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
      {assign var=unconfirmed value=0}
{section transaction $TRANSACTIONS}
        {if ($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Fee') and $TRANSACTIONS[transaction].confirmations < $GLOBAL.confirmations}
        {assign var=unconfirmed value=1}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].username}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
        {/if}
{/section}
        {if $unconfirmed != 1}
        <tr>
          <td colspan="7">No unconfirmed transactions</td>
        </tr>
        {/if}
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your estimated rewards and donations/fees for all blocks awaiting {$GLOBAL.confirmations} confirmations.</font></p>
  </center>
</div>
<div class="block_content tab_content" id="Orphan" style="">
  <center>
    <table cellpadding="1" cellspacing="1" width="98%" class="pagesort3">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Account</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
      {assign var=orphaned value=0}
{section transaction $TRANSACTIONS}
        {if ($TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Fee' or $TRANSACTIONS[transaction].type == 'Donation' or $TRANSACTIONS[transaction].type == 'Bonus') and $TRANSACTIONS[transaction].confirmations == -1}
        {assign var=orphaned value=1}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].username}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{$TRANSACTIONS[transaction].height}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Orphan_Credit' or $TRANSACTIONS[transaction].type == 'Orphan_Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
        {/if}
{/section}
        {if $orphaned != 1}
        <tr>
          <td class="center" colspan="7">No orphan transactions</td>
        </tr>
        {/if}
      </tbody>
    </table>
    <p><font color="" sizeze="1">Listed are your orphaned transactions for blocks not part of the main blockchain.</font></p>
  </center>
</div>
{include file="global/block_footer.tpl"}
