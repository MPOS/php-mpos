{if $GLOBAL.twofactor.mode == "gauth" && $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.login}
{if $USER_GAUTH == 1}
<script type="text/javascript" src="{$PATH}/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
  {literal}
  $(document).ready(function(){
    $('#qrcodeholder_ga').qrcode({
      text    : "{/literal}{$GAUTH_URL}{literal}",
      render    : "canvas",
      background : "#ffffff",
      foreground : "#000000",
      width : 250,
      height: 250 
    });
  });
  {/literal}
</script>
{/if}
<article class="module width_half">
  <header><h3>Google Authenticator</h3></header>
  <div class="module_content">
    <form action="{$smarty.server.SCRIPT_NAME}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <p>Require a Google Authenticator token when logging into your account.</p>
    <p><b style='color:red'>If you LOSE ACCESS to your Google Authenticator you will be <u>unable to login</u>{if $USER_GAUTH < 1}, and once enabled you <u>will have to reauthenticate via email to disable{/if}</u>!</b></p>
    <fieldset>
      <label>Google Authenticator</label>
      <p style="padding-left:10px;display:block;margin-top:0px;">{if $USER_GAUTH}DISABLE Google Authenticator{else}REQUIRE Google Authenticator{/if}</p>
      <label class="checkbox" for="user_gauth">
      <input class="ios-switch" type="hidden" name="user_gauth" value="0" />
      <input type="checkbox" class="ios-switch" name="user_gauth" id="user_gauth" value="1" {nocache}{if $USER_GAUTH}checked{/if}{/nocache} />
      <div class="switch"></div>
    </fieldset>
    {if $USER_GAUTH > 0}<p><b style='color:red'>REQUIRES E-MAIL CONFIRMATION TO DISABLE, YOU WILL BE LOGGED OUT</b></p>{/if}
  </div>
  <footer>
  <div class="submit_link">
  	<input type="submit" value="Update" class="alt_btn" name="update_gauth">
  	{if $GLOBAL.csrf.enabled && !"gauth"|in_array:$GLOBAL.csrf.disabled_forms}<input type="hidden" name="ctoken" value="{$CTOKEN|escape}" />{/if}
  </div>
  </footer>
</article>
{if $USER_GAUTH == 1}
<article class="module width_half">
  <header><h3>Secret</h3></header>
  <div class="module_content">
    <p>This is the secret you'll need to scan, or enter into your device to use your Google Authenticator:</p>
    <pre>
    {$GAUTH_KEY}
    </pre>
    <div id="qrcodeholder_ga"></div>
    <pre style='color:red'>
    DO NOT SHARE THIS QR CODE OR SECRET WITH ANYONE
    THEY CAN BE USED TO GENERATE A TOKEN TO LOGIN TO YOUR ACCOUNT
    </pre>
  </div>
</article>
{/if}
{if $USER_GAUTH > 0}
<article class="module width_half" {if $USER_GAUTH == 1}style="margin-top:-160px"{/if}>
  <header><h3>Reset {if $USER_GAUTH == 1}or Hide{/if} Secret</h3></header>
  <div class="module_content">
  <p><b style='color:red'>Resetting or Hiding your secret <u>will log you out</u> and you will have to <u>reauthenticate yourself</u></b></p>
  <p><b style='color:red'>If you RESET your secret, you will have to <u>reauthenticate via e-mail</u></b></p>
  <p><input type="submit" value="Reset Secret" class="alt_btn" name="reset_secret"></p>
  {if $USER_GAUTH == 1}
  <p><b style='color:red'>If you HIDE your secret, <u>MAKE SURE IT IS ADDED TO YOUR AUTHENTICATOR</u> or you will be <u>unable to login again</u> after logging out</b></p>
  <p><input type="submit" value="Hide Secret" class="alt_btn" name="hide_secret"></p>
  <p>Note: Your secret is hidden automatically after your first successful login with Google Authentication enabled.</p>
  {/if}
  </div>
  <footer></footer>
</article>
{/if}
{/if}