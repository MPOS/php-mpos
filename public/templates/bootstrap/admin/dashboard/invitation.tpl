  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">Invitations</a>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th align="center">Total</th>
              <th align="center">Activated</th>
              <th align="center">Outstanding</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td align="center">{$INVITATION_INFO.total}</td>
              <td align="center">{$INVITATION_INFO.activated}</td>
              <td align="center">{$INVITATION_INFO.outstanding}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>