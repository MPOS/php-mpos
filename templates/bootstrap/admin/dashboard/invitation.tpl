  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">{t}Invitations{/t}</a>
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>{t}Total{/t}</th>
              <th>{t}Activated{/t}</th>
              <th>{t}Outstanding{/t}</th>
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