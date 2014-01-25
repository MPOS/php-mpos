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
        {nocache}<input type="text" name="email" value="{$GLOBAL.userdata.email|escape}" size="20" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Payment Address</label>
        {nocache}<input type="text" name="paymentAddress" value="{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}" size="40"  {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Donation Percentage</label>
        <font size="1"> Donation amount in percent ({$DONATE_THRESHOLD.min}-100%)</font>
        {nocache}<input type="text" name="donatePercent" value="{$smarty.request.donatePercent|default:$GLOBAL.userdata.donate_percent|escape|number_format:"2"}" size="4" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Automatic Payout Threshold</label>
        </br>
        <font size="1" style="margin: 0px -200px;">{$GLOBAL.config.ap_threshold.min}-{$GLOBAL.config.ap_threshold.max} {$GLOBAL.config.currency}. Set to '0' for no auto payout. A {if $GLOBAL.config.txfee_auto > 0.00001}{$GLOBAL.config.txfee_auto}{else}{$GLOBAL.config.txfee_auto|number_format:"8"}{/if} {$GLOBAL.config.currency} TX fee will apply <span id="tt"><img width="15px" height="15px" title="This {if $GLOBAL.config.txfee_auto > 0.00001}{$GLOBAL.config.txfee_auto}{else}{$GLOBAL.config.txfee_auto|number_format:"8"}{/if} automatic payment transaction fee is a network fee and goes back into the network not the pool." src="site_assets/mpos/images/questionmark.png"></span></font>
        <input type="text" name="payoutThreshold" value="{nocache}{$smarty.request.payoutThreshold|default:$GLOBAL.userdata.ap_threshold|escape}{/nocache}" size="{$GLOBAL.config.ap_threshold.max|strlen}" maxlength="{$GLOBAL.config.ap_threshold.max|strlen}" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>
      </fieldset>
      <fieldset>
        <label>Anonymous Account</label>
        Hide username on website from others. Admins can still get your user information.
        <label class="checkbox" for="is_anonymous">
        <input class="ios-switch" type="hidden" name="is_anonymous" value="0" />
        {nocache}<input class="ios-switch" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" {if $GLOBAL.userdata.is_anonymous}checked{/if} {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details && !$DETAILSUNLOCKED}disabled{/if}/>{/nocache}
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
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="ea_token" value="{$smarty.request.ea_token|escape|default:""}">
        <input type="hidden" name="utype" value="account_edit">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.details}
          {if $DETAILSSENT == 1 && $DETAILSUNLOCKED == 1}
          	<input type="submit" value="Update Account" class="alt_btn">
          {elseif $DETAILSSENT == 0 && $DETAILSUNLOCKED == 1 || $DETAILSSENT == 1 && $DETAILSUNLOCKED == 0}
            <input type="submit" value="Update Account" class="alt_btn" disabled>
          {elseif $DETAILSSENT == 0 && $DETAILSUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
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
      <p style="padding-left:3px; padding-redight:30px; font-size:10px;">
        Please note: a {if $GLOBAL.config.txfee_manual > 0.00001}{$GLOBAL.config.txfee_manual}{else}{$GLOBAL.config.txfee_manual|number_format:"8"}{/if} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments <span id="tt"><img width="15px" height="15px" title="This {if $GLOBAL.config.txfee_manual > 0.00001}{$GLOBAL.config.txfee_manual}{else}{$GLOBAL.config.txfee_manual|number_format:"8"}{/if} manual payment transaction fee is a network fee and goes back into the network not the pool." src="site_assets/mpos/images/questionmark.png"></span>
      </p>
      <fieldset>
        <label>Account Balance</label>
        {nocache}<input type="text" value="{$GLOBAL.userdata.balance.confirmed|escape}" {$GLOBAL.config.currency} disabled />{/nocache}
      </fieldset>
      <fieldset>
        <label>Payout to</label>
        {nocache}<input type="text" value="{$GLOBAL.userdata.coin_address|escape}" disabled />{/nocache}
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="wf_token" value="{$smarty.request.wf_token|escape|default:""}">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="utype" value="withdraw_funds">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.withdraw}
          {if $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 1}
          	<input type="submit" value="Cash Out" class="alt_btn">
          {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 1 || $WITHDRAWSENT == 1 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Cash Out" class="alt_btn" disabled>
          {elseif $WITHDRAWSENT == 0 && $WITHDRAWUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
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
        {nocache}<input type="password" name="currentPassword" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>New Password</label>
        <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength"></p>
        {nocache}<input type="password" name="newPassword" id="pw_field"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>Repeat New Password</label>
        <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
        {nocache}<input type="password" name="newPassword2" id="pw_field2"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}disabled{/if}/>{/nocache}
      </fieldset>
      <fieldset>
        <label>4 digit PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
      {nocache}
        <input type="hidden" name="cp_token" value="{$smarty.request.cp_token|escape|default:""}">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="utype" value="change_pw">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw}
          {if $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 1}
          	<input type="submit" value="Change Password" class="alt_btn">
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 1 || $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Change Password" class="alt_btn" disabled>
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Unlock" class="alt_btn" name="unlock">
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
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
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
