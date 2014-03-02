<form action="{$smarty.server.SCRIPT_NAME}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="contactform">
    <article class="module width_3_quarter">
    <header><h3>Contact Us</h3></header>
    <div class="module_content">
      <fieldset>
        <label for="senderName">Your Name</label>
        <input type="text" class="text tiny" name="senderName" value="" placeholder="Please type your name" size="15" maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Your Email Address</label>
        <input type="text" class="text tiny" name="senderEmail" value="" placeholder="Please type your email" size="50"  maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="senderEmail">Your Subject</label>
        <input type="text" class="text tiny" name="senderSubject" value="{$smarty.request.senderSubject|escape|default:""}" placeholder="Please type your subject" size="15" maxlength="100" required />
      </fieldset>
      <fieldset>
        <label for="message">Your Message</label>
        <textarea type="text" name="senderMessage" cols="80" rows="10" maxlength="10000" required>{$smarty.request.senderMessage|escape|default:""}</textarea>
      </fieldset>
      <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
    </div>
    <footer>
      <div class="submit_link"><input type="submit" class="alt_btn" name="sendMessage" value="Send Email" /></div>
    </footer>
  </article>
</form>
