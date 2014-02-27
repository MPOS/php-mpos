  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Login with existing account</h3>
          </div>
          <div class="panel-body">
            <form role="form" action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
              <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="username" type="email" autofocus required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" name="password" type="password" value="" required>
                </div>
                <div class="checkbox">
                  <label>
                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                  </label>
                </div>
                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>