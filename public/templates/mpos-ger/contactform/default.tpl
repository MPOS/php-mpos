<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="contactform">
    <article class="module width_3_quarter">
    <header><h3>Nimm Kontakt mit uns auf</h3></header>
    <div class="module_content">
      <fieldset>
        <label for="senderName">Dein Name</label>
        <input type="text" class="text tiny" name="senderName" value="{$smarty.request.senderName|escape|default:""}" placeholder="Bitte Deinen Namen eintragen" size="15" maxlength="20" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Deine eMail Adresse</label>
        <input type="text" class="text tiny" name="senderEmail" value="{$smarty.request.senderEmail|escape|default:""}" placeholder="Bitte Deine eMail Adresse eintragen" size="15"  maxlength="20" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Betreff</label>
        <input type="text" class="text tiny" name="senderSubject" value="{$smarty.request.senderSubject|escape|default:""}" placeholder="Betreff" size="15" maxlength="20" required />
      </fieldset>
      <fieldset>
        <label for="message">NAchricht</label>
        <textarea type="text" name="senderMessage" cols="80" rows="10" maxlength="10000" required>{$smarty.request.senderMessage|escape|default:""}</textarea>
      </fieldset>
      <center>{nocache}{$RECAPTCHA|default:""}{/nocache}</center>
    </div>
    <footer>
      <div class="submit_link"><input type="submit" class="alt_btn" name="sendMessage" value="Nachricht abschicken" /></div>
    </footer>
  </article>
</form>
