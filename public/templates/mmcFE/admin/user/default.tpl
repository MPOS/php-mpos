{include file="global/block_header.tpl" BLOCK_HEADER="Query User Database"}
<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="text" class="pin" name="query" value="{$smarty.request.query|default:"%"}">
  <input type="submit" class="submit small" value="Query">
</form>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="User Information"}
<center>
{include file="global/pagination.tpl"}
</center>
<table width="100%" class="pagesort">
  <thead>
    <tr>
      <th class="center">ID</th>
      <th>Username</th>
      <th>E-Mail</th>
      <th class="right">Shares&nbsp;&nbsp;</th>
      <th class="right">Hashrate&nbsp;&nbsp;</th>
      <th class="right">Est. Donation&nbsp;&nbsp;</th>
      <th class="right">Est. Payout&nbsp;&nbsp;&nbsp;</th>
      <th class="right">Balance&nbsp;&nbsp;&nbsp;</th>
      <th class="center">Admin</th>
      <th class="center">Locked</th>
    </tr>
  </thead>
  <tbody>
{section name=user loop=$USERS|default}
    <tr>
      <td class="center">{$USERS[user].id}</td>
      <td>{$USERS[user].username}</td>
      <td>{$USERS[user].email}</td>
      <td class="right">{$USERS[user].shares}</td>
      <td class="right">{$USERS[user].hashrate}</td>
      <td class="right">{$USERS[user].payout.est_donation|number_format:"8"}</td>
      <td class="right">{$USERS[user].payout.est_payout|number_format:"8"}</td>
      <td class="right">{$USERS[user].balance|number_format:"8"}</td>
      <td class="center">
        <img src="{$PATH}/images/{if $USERS[user].is_admin}success{else}error{/if}.gif" />
      </td>
      <td class="center">
        <img src="{$PATH}/images/{if $USERS[user].is_locked}success{else}error{/if}.gif" />
      </td>
    </tr>
{sectionelse}
  <tr>
    <td colspan="9"></td>
  </tr>
{/section}
  </tbody>
  <tfoot>
    <tr>
      <th class="center">ID</th>
      <th>Username</th>
      <th>E-Mail</th>
      <th class="right">Shares&nbsp;&nbsp;</th>
      <th class="right">Hashrate</th>
      <th class="right">Est. Donation</th>
      <th class="right">Est. Payout</th>
      <th class="right">Balance</th>
      <th class="center">Admin</th>
    </tr>
  </tfoot>
</table>
{include file="global/block_footer.tpl"}
