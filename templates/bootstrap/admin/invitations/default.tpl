{nocache}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-envelope fa-fw"></i> {t}Top Inviters{/t}
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>{t}Username{/t}</th>
              <th>{t}eMail{/t}</th>
              <th>{t}Amount{/t}</th>
              <th>{t}Outstanding{/t}</th>
              <th>{t}Activated{/t}</th>
            </tr>
          </thead>
          <tbody>
            {section inviter $TOPINVITERS}
              <tr>
                <td>{$TOPINVITERS[inviter].username|escape}</td>
                <td>{$TOPINVITERS[inviter].email}</td>
                <td>{$TOPINVITERS[inviter].invitationcount}</td>
                <td>{($TOPINVITERS[inviter].invitationcount - $TOPINVITERS[inviter].activated)|number_format:"0"}</td>
                <td>{$TOPINVITERS[inviter].activated}</td>
              </tr>
            {/section}
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        <ul class="pager">
          <li class="previous {if $smarty.get.invitersstart|default:"0" <= 0}disabled{/if}">
            <a href="{if $smarty.get.invitersstart|default:"0" <= 0}#{else}{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}{/if}">&larr; {t}Prev{/t}</a>
          </li>
          <li class="next">
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}">{t}Next{/t} &rarr;</a>
          </li>
        </ul> 
      </div>
    </div>
  </div>
</div>
{/nocache}
