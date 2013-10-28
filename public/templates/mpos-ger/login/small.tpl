{if $smarty.session.AUTHENTICATED|default:"0" == 0}
<div class="login_small">
  <form action="{$smarty.server.PHP_SELF}" method="post" id="loginForm">
    <input type="hidden" name="page" value="login" />
    <input type="hidden" name="to" value="{$smarty.server.PHP_SELF}?page=dashboard" />
    <fieldset2 class="small">
      <label>Benutzername</label>
      <input type="text" name="username" size="22" maxlength="20" required />
      <fieldset2 class="small">
        <label>Passwort</label>
        <input type="password" name="password" size="22" maxlength="20" required />
      </fieldset2>
    </fieldset2>
    <input type="submit" value="Anmelden" class="alt_btn" />
  </form>
</div>
{/if}
