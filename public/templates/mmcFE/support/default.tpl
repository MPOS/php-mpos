<div class="block" style="clear:none;">
<div class="block_head">
<div class="bheadl"></div>
<div class="bheadr"></div>
<h2>Contact &amp; Support</h2>
</div>
<div class="block_content" style="padding:10px;">
{if $smarty.session.AUTHENTICATED|default}
<script language="JavaScript">
var delayseconds = 5;
function pause() {
    myTimer = setTimeout('whatToDo()', delayseconds * 1000)
    }
function whatToDo() {
document.getElementById('autopop').click();
    }
window.onload = pause;
</script>
<center><p>This product comes 'as-is' without any warranty. Please check the Apache License, Version 2.0, for details.</p></center>
<center> <p>The contact for will auto load in 5 seconds</p></center>
<center><p>If the contact form dose not auto load please click here <a id="autopop" href="#contactForm">Contact Me Here</a></p></center>
</div>
<style type="text/css" media="all">
@import url("{$PATH}/css/default.css");
</style>
<form id="contactForm" action="/include/lib/mailform.php" method="post">

<h2>Send us an email...</h2>

<ul>
<label for="senderName">Your Name:</label>
<input type="text" name="senderName" id="senderName" placeholder="Please type your name" required="required" maxlength="40" />
</br>
<label for="senderEmail">Your Email Address:</label>
<input type="email" name="senderEmail" id="senderEmail" placeholder="Please type your email address" required="required" maxlength="50" />
</br>
<label for="message" style="padding-top: .5em;">Your Message:</label>
</br></br>
<textarea name="message" id="message" placeholder="Please type your message" required="required" cols="80" rows="10" maxlength="10000"></textarea>

</ul>

<div id="formButtons">
<input type="submit" id="sendMessage" name="sendMessage" value="Send Email" />
<input type="button" id="cancel" name="cancel" value="Cancel" />
</div>

</form>
<div id="sendingMessage" class="statusMessage"><p>Sending your message. Please wait...</p></div>
<div id="successMessage" class="statusMessage"><p>Thanks for sending your message! We'll get back to you shortly.</p></div>
<div id="failureMessage" class="statusMessage"><p>There was a problem sending your message. Please try again.</p></div>
<div id="incompleteMessage" class="statusMessage"><p>Please complete all the fields in the form before sending.</p></div>
<div class="bendl"></div>
<div class="bendr"></div>
</div>
{else}
<center><h1>Contact Form Disabled For Non Authenticated Users</h1></center>
<center><p>Please <a href="{$smarty.server.PHP_SELF}?page=login">login</a> or <a href="{$smarty.server.PHP_SELF}?page=register">register</a></p>
</div> <!-- nested block ends -->
<div class="bendl"></div>
<div class="bendr"></div>
</div>
{/if}

