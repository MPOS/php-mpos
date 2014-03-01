  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">Invitations</a>
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Total</th>
              <th>Activated</th>
              <th>Outstanding</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{$INVITATION_INFO.total}</td>
              <td>{$INVITATION_INFO.activated}</td>
              <td>{$INVITATION_INFO.outstanding}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>