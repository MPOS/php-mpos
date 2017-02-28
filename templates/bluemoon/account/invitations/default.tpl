<div class="row">
  <form class="col-lg-4" action="{$smarty.server.SCRIPT_NAME}" method="POST" role="form">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Invitation
        </div>
        <span class="tools">
          <i class="fa fa-envelope"></i>
        </span>
      </div>
      <div class="widget-body">
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
        <input type="submit" value="Invite" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>

  <div class="col-lg-8">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Past Invitations
        </div>
        <span class="tools">
          <i class="fa fa-mail-reply"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="font-size:13px;">
              <tr>
                <th>E-Mail</th>
                <th>Sent</th>
                <th>Activated</th>
              </tr>
            </thead>
            <tbody>
              {section name=invite loop=$INVITATIONS}
              <tr>
                <td>{$INVITATIONS[invite].email}</td>
                <td>{$INVITATIONS[invite].time|date_format:$GLOBAL.config.date}</td>
                <td><i class="icon-{if $INVITATIONS[invite].is_activated}ok{else}cancel{/if}"></i></td>
              </tr>
              {/section}
            <tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
