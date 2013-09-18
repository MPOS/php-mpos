{if $smarty.session.AUTHENTICATED|default:"0" == 0}
  <form action="{$smarty.server.PHP_SELF}" method="post" id="loginForm">
    <input type="hidden" name="page" value="login" />
    <input type="hidden" name="to" value="{$smarty.server.PHP_SELF}?page=dashboard"}" />
    <label>Username</label>
    <input type="text" name="username" size="22" maxlength="20" required />
    <label>Password</label>
    <input type="password" name="password" size="22" maxlength="20" required />
    <input type="submit" value="Login" class="alt_btn" />
  </form>
{/if}
