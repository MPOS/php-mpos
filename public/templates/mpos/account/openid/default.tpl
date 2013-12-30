<article class="module width_quarter">
  <header><h3>Connect OpenID Account</h3></header>
  <div class="module_content">
  <form action="{$smarty.server.PHP_SELF}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
{if $smarty.request.token|default:""}
 §  <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
{/if}
    <input type="hidden" name="action" value="openid">
    <fieldset>
      <label>Username</label>
      <input type="text" class="text tiny" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
    </fieldset>
    <fieldset>
      <label>Password</label>
      <input type="password" class="text tiny" name="password1" value="" size="15" maxlength="20" required>
      <label>Repeat Password</label>
      <input type="password" class="text tiny" name="password2" value="" size="15" maxlength="20" required>
    </fieldset>
	<input type="hidden" name="email1" value="{$smarty.session.email}" />
	<input type="hidden" name="email2"value="{$smarty.session.email}" />
    <fieldset>
      <label>PIN</label>
      <input type="password" class="text pin" name="pin" value="" size="4" maxlength="4"><font size="1"> (4 digit number. <b>Remember this pin!</b>)</font>
    </fieldset>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Register" class="alt_btn">
      </div>
    </footer>
  </form>
  </div>
</article>