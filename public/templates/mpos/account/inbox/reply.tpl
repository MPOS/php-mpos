<form action="{$smarty.server.PHP_SELF}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="inbox">
    <input type="hidden" name="do" value="save_reply">
    <input type="hidden" name="message_id" value="{$MESSAGE.id|escape}">
    <article class="module width_3_quarter">
        <header><h3>Replying to {$MESSAGE.username}</h3></header>
        <div class="module_content">
            <fieldset>
                <label for="senderEmail">Your Subject</label>
                <input type="text" class="text tiny" name="subject" value="{$MESSAGE.subject|escape|default:""}" placeholder="Please type your subject" size="15" maxlength="100" required />
            </fieldset>
            <fieldset>
                <label for="message">Your Message</label>
                <textarea type="text" name="content" cols="80" rows="10" maxlength="10000" required>{$MESSAGE.content|escape|default:""}</textarea>
            </fieldset>
            <center>{nocache}{$RECAPTCHA|default:""}{/nocache}</center>
        </div>
        <footer>
            <div class="submit_link"><input type="submit" class="alt_btn" name="sendMessage" value="Send Reply" /></div>
        </footer>
    </article>
</form>