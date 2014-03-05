<div class="row">
  <form class="col-md-4" role="form" action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Login with existing account</h3>
      </div>
      <div class="panel-body">
        <fieldset>
          <div class="form-group">
            <input class="form-control" placeholder="E-mail" name="username" type="email" autofocus required>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Password" name="password" type="password" value="" required>
          </div>
        </fieldset>
      </div>
      <div class="panel-footer">
        <input type="submit" class="btn btn-success" value="Login" >
      </div>
    </div>
  </form>
</div>