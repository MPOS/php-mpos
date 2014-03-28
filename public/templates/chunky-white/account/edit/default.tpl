<form action="{$smarty.server.PHP_SELF}" method="post" class="form-horizontal">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updateAccount">
  <article class="widget col-md-5 push-right">
    <header><h3>Account Details</h3></header>
    <div class="module_content">
      <fieldset>
      <div class="control-group">
        <label class="control-label">Username</label>
        <div class="controls form-group">
          <input type="text" value="{$GLOBAL.userdata.username|escape}" readonly />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">User Id</label>
        <div class="controls form-group">
          <input type="text" value="{$GLOBAL.userdata.id}" readonly />
        </div>
      </div>
      {if !$GLOBAL.website.api.disabled}
      <div class="control-group">
        <label class="control-label">API Key</label>
        <div class="controls form-group" style="font-size: 60%">
          <a href="{$smarty.server.PHP_SELF}?page=api&action=getuserstatus&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}">{$GLOBAL.userdata.api_key}</a>
        </div>
      </div>
      {/if}
      <div class="control-group">
        <label class="control-label">E-Mail</label>
        <div class="controls form-group">
          <input type="text" name="email" value="{nocache}{$GLOBAL.userdata.email|escape}{/nocache}" size="40" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Payment Address</label>
        <div class="controls form-group">
          <input type="text" name="paymentAddress" value="{nocache}{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}{nocache}" size="40" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Donation Percentage
          <span class="help-block">Donation amount in percent (example: 0.5)</span>
        </label>
        <div class="controls form-group">
          <input type="text" name="donatePercent" value="{nocache}{$smarty.request.donatePercent|default:$GLOBAL.userdata.donate_percent|escape}{nocache}" size="4" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Automatic Payout Threshold
        <span class="help-block">{number_format($GLOBAL.config.ap_threshold.min, 7)}-{$GLOBAL.config.ap_threshold.max} {$GLOBAL.config.currency}. Set to '0' for none.</span>
        </label>
        <div class="controls form-group">
          <input type="text" name="payoutThreshold" value="{$smarty.request.payoutThreshold|default:$GLOBAL.userdata.ap_threshold|escape}" size="8" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Anonymous Account
          <span class="help-block">
            Hide username on website from non-admins.
          </span>
        </label>
        <label class="control-label checkbox" for="is_anonymous">
        <div class="controls form-group anon-switch">
          <input class="switch" type="hidden" name="is_anonymous" value="0" />
          <input class="switch" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" {if $GLOBAL.userdata.is_anonymous}checked{/if} />
          <div class="switch"></div>
        </div>
        </label>
      </div>
      <div class="control-group">
        <label class="control-label">4 digit PIN
          <span class="help-block">The PIN you chose when registering</span>
        </label>
        <div class="controls form-group">
          <input type="password" name="authPin" size="4" maxlength="4">
        </div>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Update Account" class="btn btn-primary">
      </div>
    </footer>
  </article>
</form>

{if !$GLOBAL.disable_payouts}
<form action="{$smarty.server.PHP_SELF}" method="post" class="form-horizontal">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="cashOut">
  <article class="widget col-md-5">
    <header>
      <h3>Cash Out</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
        Please note: a {number_format($GLOBAL.config.txfee, 8)} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments
      </p>
      <fieldset>
      <div class="control-group">
        <label class="control-label">Account Balance</label>
        <div class="controls form-group">
          <input type="text" value="{nocache}{number_format($GLOBAL.userdata.balance.confirmed|escape, 8)}{/nocache}" {$GLOBAL.config.currency} readonly/>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Payout to</label>
        <div class="controls form-group">
          <input type="text" value="{nocache}{$GLOBAL.userdata.coin_address|escape}{/nocache}" readonly/>
        </div> 
      </div>
      <div class="control-group">
        <label class="control-label">4 digit PIN</label>
        <div class="controls form-group">
          <input type="password" name="authPin" size="4" maxlength="4" />
        </div>
      </div>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Cash Out" class="btn btn-primary">
      </div>
    </footer>
  </article>
</form>
{/if}

<form action="{$smarty.server.PHP_SELF}" method="post" class="form-horizontal"><input type="hidden" name="act" value="updatePassword">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updatePassword">
  <article class="widget col-md-5">
    <header>
      <h3>Change Password</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
      Note: You will be redirected to login on successful completion of a password change
      </p>
      <fieldset>
      <div class="control-group">
        <label class="control-label">Current Password</label>
        <div class="controls form-group">
          <input type="password" name="currentPassword" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">New Password</label>
        <div class="controls form-group">
          <input type="password" name="newPassword" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">New Password Repeat</label>
        <div class="controls form-group">
          <input type="password" name="newPassword2" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">4 digit PIN</label>
        <div class="controls form-group">
          <input type="password" name="authPin" size="4" maxlength="4" />
        </div>
      </div>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Change Password" class="btn btn-primary">
      </div>
    </footer>
  </article>
</form>

<script>
  $(document).ready(function() { $('input[type=checkbox]')['bootstrapSwitch'](); });
</script>
