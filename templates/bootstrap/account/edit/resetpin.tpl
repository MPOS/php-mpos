<form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="genPin">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-lock fa-fw"></i> Reset PIN
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <label>Current Password</label>
              <input class="form-control" type="password" name="currentPassword">
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <input type="submit" class="btn btn-success btn-sm" value="Reset PIN">
      </div>
    </div>
  </div>
</form>