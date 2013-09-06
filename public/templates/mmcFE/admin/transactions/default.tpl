{include file="global/block_header.tpl" BLOCK_HEADER="Transaction Summary"}
<table>
  <thead>
    <tr>
    {foreach $SUMMARY as $type=>$total}
      <th>{$type}</th>
    {/foreach}
    </tr>
  </thead>
  <tbody>
    <tr>
    {foreach $SUMMARY as $type=>$total}
      <td class="right">{$total}</td>
    {/foreach}
    </tr>
  </tbody>
</table>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" ALIGN="left" BLOCK_STYLE="width: 23%" BLOCK_HEADER="Transaction Filter"}
<form action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page}" />
  <input type="hidden" name="action" value="{$smarty.request.action}" />
  <table cellpadding="1" cellspacing="1" width="100%">
    <tbody>
      <tr>
        <td class="left">
{if $COUNTTRANSACTIONS / $LIMIT > 1}
  {if $smarty.request.start|default:"0" > 0}
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&start={$smarty.request.start|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><img src="{$PATH}/images/prev.png" /></a>
  {else}
          <img src="{$PATH}/images/prev.png" />
  {/if}
{/if}
        </td>
        <td class="right">
{if $COUNTTRANSACTIONS / $LIMIT > 1}
  {if $COUNTTRANSACTIONS - $smarty.request.start|default:"0" - $LIMIT > 0}
          <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&start={$smarty.request.start|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><img src="{$PATH}/images/next.png" /></a>
  {else}
          <img src="{$PATH}/images/next.png" />
  {/if}
{/if}
        </td>
      </tr>
        <tr>
          <td class="left">Type</td>
          <td class="right">{html_options name="filter[type]" options=$TRANSACTIONTYPES selected=$smarty.request.filter.type|default:""}</td>
        </tr>
        <tr>
          <td class="left">Status</td>
          <td class="right">{html_options name="filter[status]" options=$TXSTATUS selected=$smarty.request.filter.status|default:""}</td>
        </tr>
        <tr>
          <td class="left">Account</td>
          <td class="right"><input size="20" type="text" name="filter[account]" value="{$smarty.request.filter.account|default:""}" /></td>
        </tr>
        <tr>
          <td class="left">Address</td>
          <td class="right"><input size="20" type="text" name="filter[address]" value="{$smarty.request.filter.address|default:""}" /></td>
        </tr>
        <tr>
          <td class="center" colspan="2"><input type="submit" class="submit small" value="Filter"></td>
        </tr>
    </tbody>
  </table>
</form>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" ALIGN="right" BLOCK_STYLE="width: 75%" BLOCK_HEADER="Transaction History"}
<div class="block_content" style="clear:;">
  <center>
    <table cellpadding="1" cellspacing="1" width="100%">
      <thead style="font-size:13px;">
        <tr>
          <th class="header" style="cursor: pointer;">TX #</th>
          <th class="header" style="cursor: pointer;">Account</th>
          <th class="header" style="cursor: pointer;">Date</th>
          <th class="header" style="cursor: pointer;">TX Type</th>
          <th class="header" style="cursor: pointer;">Status</th>
          <th class="header" style="cursor: pointer;">Payment Address</th>
          <th class="header" style="cursor: pointer;">Block #</th>
          <th class="header" style="cursor: pointer;">Amount</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
{section transaction $TRANSACTIONS}
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].username}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].type}</td>
          <td>
            {if $TRANSACTIONS[transaction].type == 'Credit_PPS' OR
                $TRANSACTIONS[transaction].type == 'Fee_PPS' OR
                $TRANSACTIONS[transaction].type == 'Donation_PPS' OR
                $TRANSACTIONS[transaction].type == 'Debit_MP' OR
                $TRANSACTIONS[transaction].type == 'Debit_AP' OR
                $TRANSACTIONS[transaction].type == 'TXFee' OR
                $TRANSACTIONS[transaction].confirmations >= $GLOBAL.confirmations
            }<font color="green">Confirmed</font>
            {else if $TRANSACTIONS[transaction].confirmations == -1}<font color="red">Orphaned</font>
            {else}<font color="orange">Unconfirmed</font>{/if}
            <font size="1px">({$TRANSACTIONS[transaction].confirmations|default:"n/a"})</font>
          </td>
          <td>{$TRANSACTIONS[transaction].coin_address}</td>
          <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}{if $GLOBAL.website.blockexplorer.url}<a href="{$GLOBAL.website.blockexplorer.url}{$TRANSACTIONS[transaction].blockhash}">{$TRANSACTIONS[transaction].height}</a>{else}{$TRANSACTIONS[transaction].height}{/if}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
        </tr>
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
{include file="global/block_footer.tpl"}
