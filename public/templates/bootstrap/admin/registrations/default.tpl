{nocache}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-user fa-fw"></i> Last registered Users
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <tbody>
              <tr>
                <td>
                {if $smarty.request.registeredstart|default:"0" > 0}
                  <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#registrations"><i class="fa fa-chevron-left fa-fw"></i> Previous 10</a>
                {else}
                  <i class="fa fa-chevron-left fa-fw"></i>
                {/if}
                </td>
                <td>
                  <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#registrations">Next 10 <i class="fa fa-chevron-right fa-fw"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
    
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>eMail</th>
                <th>Reg. Date</th>
                <th>Invite</th>
                <th>Invited from</th>
              </tr>
            </thead>
            <tbody>
              {section user $LASTREGISTEREDUSERS}
              <tr>
                <td>{$LASTREGISTEREDUSERS[user].id|escape}</td>
                <td>{$LASTREGISTEREDUSERS[user].mposuser}</td>
                <td>{$LASTREGISTEREDUSERS[user].email}</td>
                <td>{$LASTREGISTEREDUSERS[user].signup_timestamp|date_format:"%d/%m %H:%M:%S"}</td>
                <td>{if !$LASTREGISTEREDUSERS[user].inviter}<i class="icon-cancel">{else}<i class="icon-ok">{/if}</td>
                <td><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user&do=query&filter[account]={$LASTREGISTEREDUSERS[user].inviter}">{$LASTREGISTEREDUSERS[user].inviter}</a></td>
              </tr>
              {/section}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> 
{/nocache}