<script>
  var zxcvbnPath = "{$PATH}/js/zxcvbn/zxcvbn.js";
</script>
<script type="text/javascript" src="{$PATH}/js/pwcheck.js"></script>

<div class="row">
  <div class="col-lg-5">
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
          <div class="input-group  input-group-sm">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
          </div>
          <label>Coin Address</label>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-money fa-fw"></i></span>
            <input type="text" name="coinaddress" placeholder="Coin Address" class="form-control" value="{$smarty.post.coinaddress|escape|default:""}" size="15" required>
          </div>
          <label>Password</label> (<span id="pw_strength">Strength</span>)
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
            <input type="password" class="form-control" name="password1" placeholder="Password" value="" size="15" maxlength="100" id="pw_field" required>
          </div>
          <span id="pw_match"></span>
          <div class="input-group input-group-sm">
            <span class="input-group-addon" id="pw_match"><i class="fa fa-key fa-fw"></i></span>
            <input type="password" class="form-control" name="password2" placeholder="Repeat Password" value="" size="15" maxlength="100" id="pw_field2" required>
          </div>
          <label>Email</label>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input type="text" name="email1" placeholder="Email" class="form-control" value="{$smarty.post.email1|escape|default:""}" size="15" required>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input type="text" class="form-control" name="email2" placeholder="Repeat Email" value="{$smarty.post.email2|escape|default:""}" size="15" required>
          </div>
          <label>PIN</label>
          <font size="1">Four digit number. <b>Remember this pin!</b></font>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-shield fa-fw"></i></span>
            <input type="password" class="form-control" name="pin" placeholder="PIN" value="" size="4" maxlength="4" required>   
          </div>
          <div class="input-group input-group-sm">
            <label>
              <input type="checkbox" value="1" name="tac" id="tac"> I Accept The <a data-toggle="modal" data-target="#TAC">Terms and Conditions</a>
            </label>
          </div>
        </div>
        <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
      </div>
      <div class="panel-footer">
        <input type="submit" value="Register" class="btn btn-success btn-sm">
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
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
