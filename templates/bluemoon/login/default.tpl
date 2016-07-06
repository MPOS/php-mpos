  <br/><br/>
  <div class="row">
    <div class="col-lg-4 col-md-4 col-md-offset-4">
      <div class="sign-in-container">
        <form class="login-wrapper" role="form" action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
          <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}">
          <div class="header">
            <div class="row">
              <div class="col-md-12 col-lg-12">
                <h2>Login<i class="fa fa-lock fa-2x pull-right"></i></h2>
              </div>
            </div>
          </div>
          <div class="content">
            <div class="form-group">
              <label for="username">Benutzername</label>
              <input type="text" class="form-control input-sm" name="username" id="username" placeholder="E-mail" autofocus required>
            </div>
            <div class="form-group">
              <label for="password">Passwort</label>
              <input type="password" class="form-control input-sm" name="password" id="password" placeholder="Password" required>
            </div>
          </div>
          <div class="actions">
            <input class="btn btn-info btn-sm" name="Login" type="submit" value="Anmelden">
            <a class="link" href="{$smarty.server.SCRIPT_NAME}?page=password">Forgot your password?</a>
            <div class="clearfix"></div>
          </div>
        </form>
      </div>
    </div>
  </div>