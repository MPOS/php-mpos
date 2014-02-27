{nocache}
  <article class="module width_full" style="min-height: 150px" name="invitations" id="invitations">
    <header><h3>Top Inviters</h3></header>
    <div>
    <table cellspacing="0" class="tablesorter">
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
    
    <table class="tablesorter" cellspacing="0">
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
  </article>
{/nocache}