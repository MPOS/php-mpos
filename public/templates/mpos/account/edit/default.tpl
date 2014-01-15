<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updateAccount">
  <article class="module width_half">
    <header><h3>Account Details</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Username</label>
        <input type="text" value="{$GLOBAL.userdata.username|escape}" disabled />
      </fieldset>
      <fieldset>
        <label>User Id</label>
        <input type="text" value="{$GLOBAL.userdata.id}" disabled />
      </fieldset>
      {if !$GLOBAL.website.api.disabled}
      <fieldset>
        <label>API Key</label>
        <a href="{$smarty.server.SCRIPT_NAME}?page=api&action=getuserstatus&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}">{$GLOBAL.userdata.api_key}</a>
      </fieldset>
      {/if}
      <fieldset>
        <label>E-Mail</label>
        <input type="text" name="email" value="{nocache}{$GLOBAL.userdata.email|escape}{/nocache}" size="20" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Payment Address</label>
        <input type="text" name="paymentAddress" value="{nocache}{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}{/nocache}" size="40"  {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Donation Percentage</label>
        <font size="1"> Donation amount in percent (example: 0.5)</font>
        <input type="text" name="donatePercent" value="{nocache}{$smarty.request.donatePercent|default:$GLOBAL.userdata.donate_percent|escape}{/nocache}" size="4"  {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Automatic Payout Threshold</label>
        <font size="1">{$GLOBAL.config.ap_threshold.min}-{$GLOBAL.config.ap_threshold.max} {$GLOBAL.config.currency}. Set to '0' for no auto payout.</font>
        <input type="text" name="payoutThreshold" value="{nocache}{$smarty.request.payoutThreshold|default:$GLOBAL.userdata.ap_threshold|escape}{/nocache}" size="{$GLOBAL.config.ap_threshold.max|strlen}" maxlength="{$GLOBAL.config.ap_threshold.max|strlen}"  {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Anonymous Account</label>
        Hide username on website from others. Admins can still get your user information.
        <label class="checkbox" for="is_anonymous">
        <input class="ios-switch" type="hidden" name="is_anonymous" value="0" />
        <input class="ios-switch" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" {nocache}{if $GLOBAL.userdata.is_anonymous}checked{/if}{/nocache} {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
        <div class="switch"></div>
        </label>
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <font size="1">The 4 digit PIN you chose when registering</font>
        <input type="password" name="authPin" size="4" maxlength="4">
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="ea_token" value="{$smarty.request.ea_token|escape}">
        <input type="hidden" name="utype" value="account_edit">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details}
          {if $DETAILSUNLOCKED > 0 && $DETAILSSENT > 0}
            <input type="submit" value="Update Account" class="alt_btn">
          {else}
            {if $DETAILSSENT == 1}
              <input type="submit" value="Update Account" class="alt_btn" disabled>
            {else}
              <input type="submit" value="Unlock" class="alt_btn" name="unlock">
            {/if}
          {/if}
        {else}
          <input type="submit" value="Update Account" class="alt_btn">
        {/if}
      {/nocache}
      </div>
    </footer>
  </article>
</form>

{if !$GLOBAL.config.disable_payouts && !$GLOBAL.config.disable_manual_payouts}
<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="cashOut">
  <article class="module width_half">
    <header>
      <h3>Cash Out</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
        Please note: a {if $GLOBAL.config.txfee > 0.00001}{$GLOBAL.config.txfee}{else}{$GLOBAL.config.txfee|number_format:"8"}{/if} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments
      </p>
      <fieldset>
        <label>Account Balance</label>
        <input type="text" value="{nocache}{$GLOBAL.userdata.balance.confirmed|escape}{/nocache}" {$GLOBAL.config.currency} readonly {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw && !$WITHDRAWUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Payout to</label>
        <input type="text" value="{nocache}{$GLOBAL.userdata.coin_address|escape}{/nocache}" readonly {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw && !$WITHDRAWUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="wf_token" value="{$smarty.request.wf_token|escape}">
        <input type="hidden" name="utype" value="withdraw_funds">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw}
          {if $WITHDRAWUNLOCKED > 0 && $WITHDRAWSENT > 0}
            <input type="submit" value="Cash Out" class="alt_btn">
          {else}
            {if $WITHDRAWSENT == 1}
              <input type="submit" value="Cash Out" class="alt_btn" disabled>
            {else}
              <input type="submit" value="Unlock" class="alt_btn" name="unlock">
            {/if}
          {/if}
        {else}
          <input type="submit" value="Cash Out" class="alt_btn">
        {/if}
      {/nocache}
      </div>
    </footer>
  </article>
</form>
{/if}

<form action="{$smarty.server.SCRIPT_NAME}" method="post"><input type="hidden" name="act" value="updatePassword">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updatePassword">
  <article class="module width_half">
    <header>
      <h3>Change Password</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
      Note: You will be redirected to login on successful completion of a password change
      </p>
      <fieldset>
        <label>Current Password</label>
        <input type="password" name="currentPassword" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>New Password</label>
        <input type="password" name="newPassword" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>New Password Repeat</label>
        <input type="password" name="newPassword2" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="cp_token" value="{$smarty.request.cp_token|escape}">
        <input type="hidden" name="utype" value="change_pw">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw}
          {if $CHANGEPASSUNLOCKED > 0 && $CHANGEPASSSENT > 0}
            <input type="submit" value="Change Password" class="alt_btn">
          {else}
            {if $CHANGEPASSSENT == 1}
              <input type="submit" value="Change Password" class="alt_btn" disabled>
            {else}
              <input type="submit" value="Unlock" class="alt_btn" name="unlock">
            {/if}
          {/if}
        {else}
          <input type="submit" value="Change Password" class="alt_btn">
        {/if}
      {/nocache}
      </div>
    </footer>
  </article>
</form>


<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="genPin">
	<article class="module width_half">
	  <header>
		  <h3>Reset PIN</h3>
		</header>
		<div class="module_content">
      <fieldset>
		  <label>Current Password</label>
		  <input type="password" name="currentPassword" />
		  </fieldset>
		</div>
		<footer>
      <div class="submit_link">
        <input type="submit" class="alt_btn" value="Reset PIN">
      </div>
    </footer>
  </article>
</form>