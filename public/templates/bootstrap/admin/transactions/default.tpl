{if $DISABLE_TRANSACTIONSUMMARY|default:"0" != 1}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Transaction Summary
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
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
                <td class="right">{$total|number_format:"8"}</td>
              {/foreach}
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
{/if}

<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        Transaction Filter
      </div>
      <div class="panel-body">
        <form action="{$smarty.server.SCRIPT_NAME}" role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
          <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
          <table cellspacing="0" class="tablesorter">
            <tbody>
              <tr>
                <td>
                  {if $smarty.request.start|default:"0" > 0}
                  <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-left-open"></i></a>
                  {else}
                  <i class="icon-left-open"></i>
                  {/if}
                </td>
                <td>
                  <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-right-open"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="form-group">
            <label>Type</label>
            {html_options class="form-control" name="filter[type]" options=$TRANSACTIONTYPES selected=$smarty.request.filter.type|default:""}
          </div>
          <div class="form-group">
            <label>Status</label>
            {html_options class="form-control" name="filter[status]" options=$TXSTATUS selected=$smarty.request.filter.status|default:""}
          </div>
          <div class="form-group">
            <label>Account</label>
            <input class="form-control" size="20" type="text" name="filter[account]" value="{$smarty.request.filter.account|default:""}" />
          </div>
          <div class="form-group">
            <label>Address</label>
            <input class="form-control" size="20" type="text" name="filter[address]" value="{$smarty.request.filter.address|default:""}" />
          </div>
          <input type="submit" value="Filter" class="btn btn-outline btn-success btn-lg btn-block">
        </form>
      </div>
    </div>
  </div>



  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        Transaction History
      </div>
      <div class="panel-body no-padding">

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Account</th>
                <th>Date</th>
                <th>TX Type</th>
                <th>Status</th>
                <th>Payment Address</th>
                <th>TX #</th>
                <th>Block #</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              {section transaction $TRANSACTIONS}
              <tr>
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
                }<span class="confirmed">Confirmed</span>
                {else if $TRANSACTIONS[transaction].confirmations == -1}<span class="orphan">Orphaned</span>
                {else}<span class="unconfirmed">Unconfirmed</span>{/if}
                </td>
                <td><a href="#" onClick="alert('{$TRANSACTIONS[transaction].coin_address|escape}')">{$TRANSACTIONS[transaction].coin_address|truncate:20:"...":true:true}</a></td>
                {if ! $GLOBAL.website.transactionexplorer.disabled}
                <td><a href="{$GLOBAL.website.transactionexplorer.url}{$TRANSACTIONS[transaction].txid|escape}" title="{$TRANSACTIONS[transaction].txid|escape}">{$TRANSACTIONS[transaction].txid|truncate:20:"...":true:true}</a></td>
                {else}
                <td><a href="#" onClick="alert('{$TRANSACTIONS[transaction].txid|escape}')" title="{$TRANSACTIONS[transaction].txid|escape}">{$TRANSACTIONS[transaction].txid|truncate:20:"...":true:true}</a></td>
                {/if}
                <td>{if $TRANSACTIONS[transaction].height == 0}n/a{else}<a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=round&height={$TRANSACTIONS[transaction].height}">{/if}{$TRANSACTIONS[transaction].height}</a></td>
                <td><font color="{if $TRANSACTIONS[transaction].type == 'Credit' or $TRANSACTIONS[transaction].type == 'Credit_PPS' or $TRANSACTIONS[transaction].type == 'Bonus'}green{else}red{/if}">{$TRANSACTIONS[transaction].amount|number_format:"8"}</td>
              </tr>
              {/section}
            </tbody>
          </table>
          <footer><font size="1"><b>Credit_AP</b> = Auto Threshold Payment, <b>Credit_MP</b> = Manual Payment, <b>Donation</b> = Donation, <b>Fee</b> = Pool Fees (if applicable)</font></footer>
        </div>
      </div>
    </div>
  </div>
<div
