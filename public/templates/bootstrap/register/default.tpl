<script>
  var zxcvbnPath = "{$PATH}/js/zxcvbn/zxcvbn.js";
</script>
<script type="text/javascript" src="{$PATH}/js/pwcheck.js"></script>

<div class="row">
  <div class="col-lg-6">
    <form class="panel panel-info" method="post" role="form">
      <input type="hidden" name="page" value="{$smarty.request.page|escape}">
      {if $smarty.request.token|default:""}
      <input type="hidden" name="token" value="{$smarty.request.token|escape}">
      {/if}
      <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}">
      <input type="hidden" name="action" value="register">
      <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i> Register new account
      </div>
      <div class="panel-body">
        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <span style="padding-right:10px;display:block;margin-top:1px;float:right;color:#999;" id="pw_strength">Strength</span>
          <input type="password" class="form-control" name="password1" value="" size="15" maxlength="100" id="pw_field" required>
          <label>Repeat Password</label>
          <span style="padding-right:10px;display:block;margin-top:1px;float:right;" id="pw_match"></span>
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
          <input type="password" class="form-control" name="pin" value="" size="4" maxlength="4"><font size="1">Four digit number. <b>Remember this pin!</b></font>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" value="1" name="tac" id="tac">
              I Accept The <a data-toggle="modal" data-target="#TAC">Terms and Conditions</a>
            </label>
          </div>
        </div>
        <center>{nocache}{$RECAPTCHA|default:""}{/nocache}</center>
      </div>
      <div class="panel-footer">
        <input type="submit" value="Register" class="btn btn-success">
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="TAC" tabindex="-1" role="dialog" aria-labelledby="TACLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="TACLabel">Terms and Conditions</h4>
        </div>
        <div class="modal-body">
          {include file="tac/content.tpl"}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
