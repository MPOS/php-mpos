<script>
  var zxcvbnPath = "{$GLOBALASSETS}/js/plugins/zxcvbn/zxcvbn.js";
</script>
<script type="text/javascript" src="{$GLOBALASSETS}/js/pwcheck.js"></script>

<br/><br/>
<div class="row">
  <div class="col-lg-6 col-md-6 col-md-offset-3">
    <div class="sign-in-container">
      <form class="login-wrapper" role="form" action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
        <input type="hidden" name="page" value="password">
        <input type="hidden" name="action" value="reset">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="header">
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <h2>Register new account<i class="fa fa-edit fa-2x pull-right"></i></h2>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-6">
                <label>Username</label>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                  <input type="text" class="form-control" name="username" placeholder="Username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
                </div>
                {if $GLOBAL.coinaddresscheck|default:"1"}
                <label>Coin Address</label>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-money fa-fw"></i></span>
                  <input type="text" name="coinaddress" placeholder="Coin Address" class="form-control" value="{$smarty.post.coinaddress|escape|default:""}" size="15" required>
                </div>
                {/if}
                <label>PIN</label>
                <font size="1">Four digit number. <b>Remember this pin!</b></font>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-shield fa-fw"></i></span>
                  <input type="password" class="form-control" name="pin" placeholder="PIN" value="" size="4" maxlength="4" required>   
                </div>
                <div class="input-group margin-bottom-sm">
                  <label>
                    <input type="checkbox" value="1" name="tac" id="tac"> I Accept The <a data-toggle="modal" data-target="#TAC">Terms and Conditions</a>
                  </label>
                </div>
              </div>
              <div class="col-lg-6">
                <label>Email</label>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                  <input type="text" name="email1" placeholder="Email" class="form-control" value="{$smarty.post.email1|escape|default:""}" size="15" required>
                </div>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                  <input type="text" class="form-control" name="email2" placeholder="Repeat Email" value="{$smarty.post.email2|escape|default:""}" size="15" required>
                </div>
                <label>Password</label> (<span id="pw_strength">Strength</span>)
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                  <input type="password" class="form-control" name="password1" placeholder="Password" value="" size="15" maxlength="100" id="pw_field" required>
                </div>
                <span id="pw_match"></span>
                <div class="input-group margin-bottom-sm">
                  <span class="input-group-addon" id="pw_match"><i class="fa fa-key fa-fw"></i></span>
                  <input type="password" class="form-control" name="password2" placeholder="Repeat Password" value="" size="15" maxlength="100" id="pw_field2" required>
                </div>
              </div>
            </div>
            <div class="row">
              <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
            </div>
          </div>
        </div>
        <div class="actions">
          <input type="submit" value="Register" class="btn btn-success btn-sm">
          <div class="clearfix"></div>
        </div>
      </form>
    </div>
  </div>
</div>