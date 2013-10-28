<article class="module width_half">
  <form action="{$smarty.server.PHP_SELF}?page=login" method="post" id="loginForm">
    <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.PHP_SELF}?page=dashboard")|escape}" />
    <header><h3>Login with existing account</h3></header>
    <div class="module_content">
        <fieldset>
          <label>Benutzername oder eMail Adresse</label>
          <input type="text" name="username" size="22" maxlength="20" value="{$smarty.request.username|default:""|escape}" placeholder="Dein Benutzername oder eMail Adresse" required />
        </fieldset>
        <fieldset>
          <label>Passwort</label>
          <input type="password" name="password" size="22" maxlength="20" placeholder="Dein Passwort" required />
        </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      <div class="submit_link">
        <a href="{$smarty.server.PHP_SELF}?page=password"><font size="1">Passwort vergessen?</font></a>
        <input type="submit" value="Anmelden" class="alt_btn" />
      </div>
    </footer>
  </form>
</article>
