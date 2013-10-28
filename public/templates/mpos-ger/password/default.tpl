<article class="module width_half">
  <form action="" method="POST">
    <input type="hidden" name="page" value="password">
    <input type="hidden" name="action" value="reset">
    <header><h3>Passwort zur&uuml;cksetzen</h3></header>
    <div class="module_content">
      <p>Wenn eine eMail Adresse hinterlegt ist, trage bitte Deinen Benutzernamen ein um ein neues Passwort zu bekommen</p>
      <fieldset>
        <label>Benutzername oder eMail Adresse</label>
        <input type="text" name="username" value="{$smarty.post.username|default:""}" size="22" maxlength="20" required>
      </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Zur&uuml;cksetzen" class="alt_btn">
      </div>
    </footer>
  </form>
</article>
