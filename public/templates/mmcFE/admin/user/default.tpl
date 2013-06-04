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
  <div id="pager">
  <form>
    <img src="{$PATH}/images/first.png" class="first"/>
    <img src="{$PATH}/images/prev.png" class="prev"/>
    <input type="text" class="pagedisplay"/>
    <img src="{$PATH}/images/next.png" class="next"/>
    <img src="{$PATH}/images/last.png" class="last"/>
    <select class="pagesize">
      <option selected="selected"  value="10">10</option>
      <option value="20">20</option>
      <option value="30">30</option>
      <option  value="40">40</option>
    </select>
  </form>
  </div>
</center>
<table width="100%" class="pagesort">
  <colgroup>
    <col style="width: 25px">
    <col style="width: 120px">
    <col style="width: 120px">
    <col style="width: 60px">
    <col style="width: 60px">
    <col style="width: 60px">
    <col style="width: 60px">
    <col style="width: 60px">
    <col style="width: 60px">
    <col style="width: 60px">
  </colgroup>
  <thead>
    <tr>
      <th class="center">ID</th>
      <th>Username</th>
      <th>E-Mail</th>
      <th class="right">Hashrate</th>
      <th class="right">Valid</th>
      <th class="right">Invalid</th>
      <th class="right">Balance</th>
      <th class="right">Est. Donation</th>
      <th class="right">Est. Payout</th>
      <th class="center">Admin</th>
    </tr>
  </thead>
  <tbody>
{section name=user loop=$USERS|default}
    <tr>
      <td class="center">{$USERS[user].id}</td>
      <td>{$USERS[user].username}</td>
      <td>{$USERS[user].email}</td>
      <td class="right">{$USERS[user].hashrate}</td>
      <td class="right">{$USERS[user].shares.valid}</td>
      <td class="right">{$USERS[user].shares.invalid}</td>
      <td class="right">{$USERS[user].balance}</td>
      <td class="right">{$USERS[user].payout.est_donation}</td>
      <td class="right">{$USERS[user].payout.est_payout}</td>
      <td class="center">
        <img src="{$PATH}/images/{if $USERS[user].admin}success{else}error{/if}.gif" />
      </td>
    </tr>
{/section}
  </tbody>
  <tfoot>
    <tr>
      <th class="center">ID</th>
      <th>Username</th>
      <th>E-Mail</th>
      <th class="right">Hashrate</th>
      <th colspan="2" class="center">Shares</th>
      <th class="right">Balance</th>
      <th class="right">Est. Donation</th>
      <th class="right">Est. Payout</th>
      <th class="center">Admin</th>
    </tr>
  </tfoot>
</table>
{include file="global/block_footer.tpl"}
