<article class="module width_half">
  <form action="{$smarty.server.PHP_SELF}?page=login" method="post" id="loginForm">
    <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.PHP_SELF}?page=dashboard")|escape}" />
    <header><h3>Login with existing account</h3></header>
    <div class="module_content">
        <fieldset>
          <label>Username or E-Mail</label>
          <input type="text" name="username" size="22" maxlength="100" value="{$smarty.request.username|default:""|escape}" placeholder="Your username or email" required />
        </fieldset>
        <fieldset>
          <label>Password</label>
          <input type="password" name="password" size="22" maxlength="100" placeholder="Your password" required />
        </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      <div class="submit_link">
        <a href="{$smarty.server.PHP_SELF}?page=password"><font size="1">Forgot your password?</font></a>
        <input type="submit" value="Login" class="alt_btn" />
      </div>
    </footer>
  </form>
</article>
