  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">Invitations</a>
        </div>
        <span class="tools">
          <i class="fa fa-users"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
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
  </div>