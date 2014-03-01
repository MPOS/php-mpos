{nocache}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Top Inviters
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">

    <tbody>
      <tr>
        <td>
{if $smarty.request.invitersstart|default:"0" > 0}
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#invitations"><i class="icon-left-open"></i> Previous 10</a>
{else}
          <i class="icon-left-open"></i>
{/if}
        </td>
        <td>
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#invitations">Next 10 <i class="icon-right-open"></i></a>
        </td>
    </tbody>
  </table>
    
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>Username</th>
          <th>eMail</th>
          <th>Amount</th>
          <th>Outstanding</th>
          <th>Activated</th>
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
    </div>
  </div>
</div>
{/nocache}