<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Register new account
      </div>
      <div class="panel-body">
        <form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}">
          {if $smarty.request.token|default:""}
          <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
          {/if}
          <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
          <input type="hidden" name="action" value="register">
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
          </div>
          <div class="form-group">
            <label>Password</label> 
            <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength">Strength</p>
            <input type="password" class="form-control" name="password1" value="" size="15" maxlength="100" id="pw_field" required>
            <label>Repeat Password</label>
            <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
            <input type="password" class="form-control" name="password2" value="" size="15" maxlength="100" id="pw_field2" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="email1" class="form-control" value="{$smarty.post.email1|escape|default:""}" size="15" required>
            <label>Email Repeat</label>
            <input type="text" class="form-control" name="email2" value="{$smarty.post.email2|escape|default:""}" size="15" required>
          </div>
          <div class="form-group">
            <label>PIN</label>
            <input type="password" class="form-control" name="pin" value="" size="4" maxlength="4"><font size="1"> (4 digit number. <b>Remember this pin!</b>)</font>
          </fieldset>
          <div class="form-group">
            <label>Terms and Conditions</label><a style="width:152px;" onclick="TINY.box.show({literal}{url:'?page=tacpop',height:500}{/literal})"><font size="1">Accept Terms and Conditions</font></a>
            <input type="checkbox" value="1" name="tac" id="tac">
            <label for="tac" style="margin:1px 0px 0px -20px"></label>
          </div>
          <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
          <input type="submit" value="Register" class="btn btn-outline btn-success btn-lg btn-block">
        </form>
      </div>
    </div>
  </div>
</div>