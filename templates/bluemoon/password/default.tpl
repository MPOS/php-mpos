<br/><br/>
<div class="row">
  <div class="col-lg-4 col-md-4 col-md-offset-4">
    <div class="sign-in-container">
      <form class="login-wrapper" role="form" action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
        <input type="hidden" name="page" value="password">
        <input type="hidden" name="action" value="reset">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="header">
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <h2>Password reset<i class="fa fa-refresh fa-2x pull-right"></i></h2>
            </div>
          </div>
        </div>
        <div class="content">
          <p>If you have an email set for your account, enter your username to get your password reset</p>
          <div class="input-group margin-bottom-sm">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input class="form-control" placeholder="Username or E-mail" name="username" type="text" maxlength="100" autofocus required>
          </div>
        </div>
        <div class="actions">
          <input type="submit" class="btn btn-success btn-sm" value="Reset" >
          <div class="clearfix"></div>
        </div>
      </form>
    </div>
  </div>
</div>