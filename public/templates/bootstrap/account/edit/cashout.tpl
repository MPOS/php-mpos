{if !$GLOBAL.config.disable_payouts && !$GLOBAL.config.disable_manual_payouts}
<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="cashOut">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Cash Out
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <p style="padding-left:3px; padding-redight:30px; font-size:10px;">
                Please note: a {if $GLOBAL.config.txfee_manual > 0.00001}{$GLOBAL.config.txfee_manual}{else}{$GLOBAL.config.txfee_manual|number_format:"8"}{/if} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments <span id="tt"><img width="15px" height="15px" title="This {if $GLOBAL.config.txfee_manual > 0.00001}{$GLOBAL.config.txfee_manual}{else}{$GLOBAL.config.txfee_manual|number_format:"8"}{/if} manual payment transaction fee is a network fee and goes back into the network not the pool." src="site_assets/mpos/images/questionmark.png"></span>
              </p>
            </div>
            <div class="form-group">
              <label>Account Balance</label>
              {nocache}<input class="form-control" id="disabledInput" type="text" value="{$GLOBAL.userdata.balance.confirmed|escape}" {$GLOBAL.config.currency} disabled />{/nocache}
            </div>
            <div class="form-group">
              <label>Payout to</label>
              {nocache}<input class="form-control" id="disabledInput" type="text" value="{$GLOBAL.userdata.coin_address|escape}" disabled />{/nocache}
            </div>
            <div class="form-group">
              <label>4 digit PIN</label>
              <input class="form-control" type="password" name="authPin" size="4" maxlength="4" />
            </div>
            {nocache}
            <input type="hidden" name="wf_token" value="{$smarty.request.wf_token|escape|default:""}">
            <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
            <input type="hidden" name="utype" value="withdraw_funds">
            {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw}
            {if $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 1}
            <input type="submit" value="Cash Out" class="btn btn-outline btn-success btn-lg btn-block">
            {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 1 || $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Cash Out" class="btn btn-outline btn-danger btn-lg btn-block" disabled>
            {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Unlock" class="btn btn-outline btn-warning btn-lg btn-block" name="unlock">
            {/if}
            {else}
            <input type="submit" value="Cash Out" class="btn btn-outline btn-success btn-lg btn-block">
            {/if}
            {/nocache}
          </div>
        </div>
      </div>      
    </div>
  </div>
</form>
{/if}