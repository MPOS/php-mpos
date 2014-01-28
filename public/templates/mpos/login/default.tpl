<article class="module width_half">
  <form action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
    <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
    <header><h3>Login with existing account</h3></header>
    <div class="module_content">
        <fieldset>
          <label>E-Mail</label>
          <input type="email" name="username" size="22" maxlength="100" value="{$smarty.request.username|default:""|escape}" placeholder="Your email" tabindex="1" required />
        </fieldset>
        <fieldset>
          <label>Password</label>
          <input type="password" name="password" size="22" maxlength="100" placeholder="Your password" tabindex="2" required />
        </fieldset>
      <div class="clear"></div>
    </div>
    <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
    <footer>
      <div class="submit_link">
        <a href="{$smarty.server.SCRIPT_NAME}?page=password"><font size="1">Forgot your password?</font></a>
        <input type="submit" value="Login" class="alt_btn" />
      </div>
    </footer>
  </form>
</article>
