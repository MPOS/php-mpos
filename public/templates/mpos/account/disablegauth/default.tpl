<article class="module width_half">
  <form action="{$smarty.server.SCRIPT_NAME}?page=account&action=disablegauth" method="post" id="loginForm">
    <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.SCRIPT_NAME}?page=dashboard")|escape}" />
    <input type="hidden" name="ctoken" value="{$CTOKEN|escape}" />
    <header><h3>Confirm removal of Google Authentication</h3></header>
    <div class="module_content">
        <fieldset>
          <label>Confirmation Token</label>
          <input type="text" name="da_token" size="22" maxlength="100" value="{$smarty.request.da_token|default:""|escape}" placeholder="Token provided by your confirmation e-mail" required />
        </fieldset>
        <fieldset>
          <label>E-Mail</label>
          <input type="email" name="username" size="22" maxlength="100" value="{$smarty.request.username|default:""|escape}" placeholder="Your email" required />
        </fieldset>
        <fieldset>
          <label>Password</label>
          <input type="password" name="password" size="22" maxlength="100" placeholder="Your password" required />
        </fieldset>
        <fieldset>
          <label>4 digit PIN</label>
          <font size="1">The 4 digit PIN you chose when registering</font>
          <input type="password" name="authPin" size="4" maxlength="4">
      	</fieldset>
      <div class="clear"></div>
    </div>
    <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
    <footer>
      <div class="submit_link">
        <a href="{$smarty.server.SCRIPT_NAME}?page=password"><font size="1">Forgot your password?</font></a>
        <input type="submit" value="Confirm Removal" class="alt_btn" name="confirm_dga" />
      </div>
    </footer>
  </form>
</article>
