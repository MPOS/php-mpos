{nocache}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-user fa-fw"></i> {t}Last Registered Users{/t}
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>{t}ID{/t}</th>
                <th>{t}Username{/t}</th>
                <th>{t}eMail{/t}</th>
                <th>{t}Reg. Date{/t}</th>
                <th>{t}Invite{/t}</th>
                <th>{t}Invited From{/t}</th>
              </tr>
            </thead>
            <tbody>
              {section user $LASTREGISTEREDUSERS}
              <tr>
                <td>{$LASTREGISTEREDUSERS[user].id|escape}</td>
                <td>{$LASTREGISTEREDUSERS[user].mposuser}</td>
                <td>{$LASTREGISTEREDUSERS[user].email}</td>
                <td>{$LASTREGISTEREDUSERS[user].signup_timestamp|date_format:$GLOBAL.config.date}</td>
                <td class="text-center">{if !$LASTREGISTEREDUSERS[user].inviter}<i class="fa fa-times fa-fw">{else}<i class="fa fa-check fa-fw">{/if}</td>
                <td><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user&do=query&filter[account]={$LASTREGISTEREDUSERS[user].inviter}">{$LASTREGISTEREDUSERS[user].inviter}</a></td>
              </tr>
              {/section}
            </tbody>
          </table>
        </div>
      </div>
      <div class="panel-footer">
        <ul class="pager">
          <li class="previous {if $smarty.get.registeredstart|default:"0" <= 0}disabled{/if}">
            <a href="{if $smarty.get.registeredstart|default:"0" <= 0}#{else}{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}{/if}">&larr; {t}Prev{/t}</a>
          </li>
          <li class="next">
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#registrations">{t}Next{/t} &rarr;</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div> 
{/nocache}
