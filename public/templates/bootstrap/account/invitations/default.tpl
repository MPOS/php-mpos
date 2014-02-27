<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        Invitation
      </div>
      <div class="panel-body">
      
        <form action="{$smarty.server.SCRIPT_NAME}" method="POST" role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}">
          <input type="hidden" name="action" value="{$smarty.request.action|escape}">
          <input type="hidden" name="do" value="sendInvitation">
          <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />

          <div class="form-group">
            <label>E-Mail</label>
            <input class="form-control" type="text" name="data[email]" value="{$smarty.request.data.email|escape|default:""}" size="30" />
          </div>
          <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="data[message]" rows="5">{$smarty.request.data.message|escape|default:"Please accept my invitation to this awesome pool."}</textarea>
          </div>
          <input type="submit" value="Invite" class="btn btn-outline btn-success btn-lg btn-block">
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        Past Invitations
      </div>
      <div class="panel-body">
      
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="font-size:13px;">
              <tr>
                <th>E-Mail</th>
                <th align="center">Sent</th>
                <th align="center">Activated</th>
              </tr>
            </thead>
            <tbody>
{section name=invite loop=$INVITATIONS}
              <tr>
                <td>{$INVITATIONS[invite].email}</td>
                <td align="center">{$INVITATIONS[invite].time|date_format:"%d/%m/%Y %H:%M:%S"}</td>
                <td align="center"><i class="icon-{if $INVITATIONS[invite].is_activated}ok{else}cancel{/if}"></i></td>
              </tr>
{/section}
            <tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

