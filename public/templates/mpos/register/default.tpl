
<form action="{$smarty.server.SCRIPT_NAME}" method="post">
<article class="module width_half">
  <header><h3>Register new account</h3></header>
  <div class="module_content">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
{if $smarty.request.token|default:""}
    <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
{/if}
	<input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
    <input type="hidden" name="action" value="register">
    <fieldset>
      <label>Username</label>
      <input type="text" class="text tiny" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
    </fieldset>
    <fieldset>
      <label>Password</label> 
      <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength">Strength</p>
      <input type="password" class="text tiny" name="password1" value="" size="15" maxlength="100" id="pw_field" required>
      <label>Repeat Password</label>
      <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
      <input type="password" class="text tiny" name="password2" value="" size="15" maxlength="100" id="pw_field2" required>
    </fieldset>
    <fieldset>
      <label>Email</label>
      <input type="text" name="email1" class="text small" value="{$smarty.post.email1|escape|default:""}" size="15" required>
      <label>Email Repeat</label>
      <input type="text" class="text small" name="email2" value="{$smarty.post.email2|escape|default:""}" size="15" required>
    </fieldset>
    <fieldset>
      <label>PIN</label>
      <input type="password" class="text pin" name="pin" value="" size="4" maxlength="4"><font size="1"> (4 digit number. <b>Remember this pin!</b>)</font>
    </fieldset>
    <fieldset>
      <label>Terms and Conditions</label><a style="width:152px;" onclick="TINY.box.show({literal}{url:'?page=tacpop',height:500}{/literal})"><font size="1">Accept Terms and Conditions</font></a>
      <input type="checkbox" value="1" name="tac" id="tac">
      <label for="tac" style="margin:1px 0px 0px -20px"></label>
    </fieldset>
    <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
  </div>
  <footer>
    <div class="submit_link">
      <input type="submit" value="Register" class="alt_btn">
    </div>
  </footer>
</article>
</form>
