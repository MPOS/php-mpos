<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="genPin">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Reset PIN
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group"">
              <label>Current Password</label>
              <input class="form-control" type="password" name="currentPassword" />
            </div>
            <input type="submit" class="btn btn-outline btn-success btn-lg btn-block" value="Reset PIN">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>