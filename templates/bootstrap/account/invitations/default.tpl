<div class="row">
  <form class="col-lg-4" action="{$smarty.server.SCRIPT_NAME}" method="POST" role="form">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-envelope fa-fw"></i> {t}Invitation{/t}
      </div>
      <div class="panel-body">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}">
        <input type="hidden" name="action" value="{$smarty.request.action|escape}">
        <input type="hidden" name="do" value="sendInvitation">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="form-group">
          <label>{t}E-Mail{/t}</label>
          <input class="form-control" type="text" name="data[email]" value="{$smarty.request.data.email|escape|default:""}" size="30" />
        </div>
        <div class="form-group">
          <label>{t}Message{/t}</label>
          <textarea class="form-control" name="data[message]" rows="5">{$smarty.request.data.message|escape|default:"{t}Please accept my invitation to this awesome pool.{/t}"}</textarea>
        </div>
      </div>
      <div class="panel-footer">
        <input type="submit" value="Invite" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>

  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-mail-reply fa-fw"></i> {t}Past Invitations{/t}
      </div>
      <div class="panel-body">
      
        <div class="table-responsive">
          <table class="table table-hover">
            <thead style="font-size:13px;">
              <tr>
                <th>{t}E-Mail{/t}</th>
                <th>{t}Sent{/t}</th>
                <th>{t}Activated{/t}</th>
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
