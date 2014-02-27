{nocache}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Top Inviters
      </div>
      <div class="panel-body">
        <table class="table">

    <tbody>
      <tr>
        <td align="left">
{if $smarty.request.invitersstart|default:"0" > 0}
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#invitations"><i class="icon-left-open"></i> Previous 10</a>
{else}
          <i class="icon-left-open"></i>
{/if}
        </td>
        <td align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&invitersstart={$smarty.request.invitersstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#invitations">Next 10 <i class="icon-right-open"></i></a>
        </td>
    </tbody>
  </table>
    
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th align="left">eMail</th>
          <th align="center">Amount</th>
          <th align="center">Outstanding</th>
          <th align="center">Activated</th>
        </tr>
      </thead>
      <tbody>
{section inviter $TOPINVITERS}
        <tr class="{cycle values="odd,even"}">
          <td align="left">{$TOPINVITERS[inviter].username|escape}</td>
          <td align="left">{$TOPINVITERS[inviter].email}</td>
          <td align="center">{$TOPINVITERS[inviter].invitationcount}</td>
          <td align="center">{($TOPINVITERS[inviter].invitationcount - $TOPINVITERS[inviter].activated)|number_format:"0"}</td>
          <td align="center">{$TOPINVITERS[inviter].activated}</td>
        </tr>
{/section}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
{/nocache}