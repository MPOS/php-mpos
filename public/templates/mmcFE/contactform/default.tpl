<div class="block" style="clear:none;">
<div class="block_head">
<div class="bheadl"></div>
<div class="bheadr"></div>
<h2>Contact &amp; Support</h2>
</div>
<div class="block_content" style="padding:10px;">
<center><p>This product comes 'as-is' without any warranty. Please check the Apache License, Version 2.0, for details.</p></center>
</br>
<style type="text/css" media="all">
@import url("{$PATH}/css/default.css");
</style>
<form action="{$smarty.server.PHP_SELF}" method="post">
<input type="hidden" name="page" value="{$smarty.request.page|escape}">
<input type="hidden" name="action" value="contactform">
<ul>
<label for="senderName">Your Name:</label>
<input type="text" class="text tiny" name="senderName" value="{$smarty.request.senderName|escape|default:""}" placeholder="Please type your name" size="15" required="required" maxlength="20" />
</br>
<label for="senderEmail">Your Email Address:</label>
<input type="text" class="text tiny" name="senderEmail" value="{$smarty.request.senderEmail|escape|default:""}" placeholder="Please type your email" size="15" required="required" maxlength="20" />
</br>
<label for="senderEmail">Your Subject:</label>
<input type="text" class="text tiny" name="senderSubject" value="{$smarty.request.senderSubject|escape|default:""}" placeholder="Please type your subject" size="15" required="required" maxlength="20" />
</br>
<label for="message" style="padding-top: .5em;">Your Message:</label>
</br></br>
<textarea type="text" name="senderMessage" required="required" cols="80" rows="10" maxlength="10000">{$smarty.request.senderMessage|escape|default:""}</textarea>
</ul>
{nocache}{$RECAPTCHA|default:""}{/nocache}
<div id="formButtons">
<input type="submit" id="sendMessage" name="sendMessage" value="Send Email" />
<input type="reset" id="cancel" name="cancel" value="Cancel" />
</div>
</form>
</div>
