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
        <tr class="{cycle values="odd,even"}">
          <td>{$TRANSACTIONS[transaction].id}</td>
          <td>{$TRANSACTIONS[transaction].timestamp}</td>
          <td>{$TRANSACTIONS[transaction].transType}</td>
          <td>{$TRANSACTIONS[transaction].sendAddress}</td>
          <td>{if $TRANSACTIONS[transaction].assocBlock == 0}n/a{else}{$TRANSACTIONS[transaction].assocBlock}{/if}</td>
          <td><font color="{if $TRANSACTIONS[transaction].amount > 0}green{else}red{/if}">{$TRANSACTIONS[transaction].amount}</td>
        </tr>
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
          <th>Block #</th>
          <th>Estimated Reward</th>
          <th>Valid Shares</th>
          <th>Donation / Fee</th>
          <th>Validity</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
        <tr>
          <td>TODO</td>
          <td>TODO</td>
          <td>TODO</td>
          <td>TODO</td>
          <td>TODO</td>
        </tr>
        <tr>
          <td><b>Unconfirmed Totals:</b></td>
          <td><b>0.00000000</b></td>
          <td></td>
          <td><b>0.00000000</b></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </center>
</div>
{include file="global/block_footer.tpl"}
