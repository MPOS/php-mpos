{nocache}
<article class="module width_full" style="min-height: 150px" name="registrations" id="registrations">
  <header><h3>Last registered Users</h3></header>

  <table cellspacing="0" class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
{if $smarty.request.registeredstart|default:"0" > 0}
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#registrations"><i class="icon-left-open"></i> Previous 10</a>
{else}
          <i class="icon-left-open"></i>
{/if}
        </td>
        <td align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&registeredstart={$smarty.request.registeredstart|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}#registrations">Next 10 <i class="icon-right-open"></i></a>
        </td>
    </tbody>
  </table>
    
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">ID</th>
        <th>Username</th>
        <th align="left">eMail</th>
        <th align="center">Reg. Date</th>
        <th align="center">Invite</th>
        <th align="center">Invited from</th>
      </tr>
    </thead>
    <tbody>
{section user $LASTREGISTEREDUSERS}
      <tr class="{cycle values="odd,even"}">
        <td align="center">{$LASTREGISTEREDUSERS[user].id|escape}</td>
        <td>{$LASTREGISTEREDUSERS[user].mposuser}</td>
        <td align="left">{$LASTREGISTEREDUSERS[user].email}</td>
        <td align="center">{$LASTREGISTEREDUSERS[user].signup_timestamp|date_format:"%d/%m %H:%M:%S"}</td>
        <td align="center">{if !$LASTREGISTEREDUSERS[user].inviter}<i class="icon-cancel">{else}<i class="icon-ok">{/if}</td>
        <td align="center"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user&do=query&filter[account]={$LASTREGISTEREDUSERS[user].inviter}">{$LASTREGISTEREDUSERS[user].inviter}</a></td>
      </tr>
{/section}
    </tbody>
  </table>
</article>  
{/nocache}