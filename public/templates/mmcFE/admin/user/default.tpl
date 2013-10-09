<script language="javascript">
    function storeFee(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.PHP_SELF}",
       data: "page={$smarty.request.page}&action={$smarty.request.action}&do=fee&account_id=" + id,
     });
    }
    function storeLock(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.PHP_SELF}",
       data: "page={$smarty.request.page}&action={$smarty.request.action}&do=lock&account_id=" + id,
     });
    }
    function storeAdmin(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.PHP_SELF}",
       data: "page={$smarty.request.page}&action={$smarty.request.action}&do=admin&account_id=" + id,
     });
    }
</script>

{include file="global/block_header.tpl" BLOCK_HEADER="Query User Database"}
<form action="{$smarty.server.PHP_SELF}" method="POST" id='query'>
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
      <th class="center">No Fees</th>
    </tr>
  </thead>
  <tbody>
{nocache}
{section name=user loop=$USERS|default}
    <tr>
      <td class="center">{$USERS[user].id}</td>
      <td>{$USERS[user].username|escape}</td>
      <td>{$USERS[user].email|escape}</td>
      <td class="right">{$USERS[user].shares}</td>
      <td class="right">{$USERS[user].hashrate}</td>
      <td class="right">{$USERS[user].payout.est_donation|number_format:"8"}</td>
      <td class="right">{$USERS[user].payout.est_payout|number_format:"8"}</td>
      <td class="right">{$USERS[user].balance|number_format:"8"}</td>
      <td class="center">
        <input type="hidden" name="admin[{$USERS[user].id}]" value="0"/>
        <input type="checkbox" onclick="storeAdmin({$USERS[user].id})" name="admin[{$USERS[user].id}]" value="1" id="admin[{$USERS[user].id}]" {if $USERS[user].is_admin}checked{/if} />
        <label for="admin[{$USERS[user].id}]"></label>
      </td>
      <td class="center">
        <input type="hidden" name="locked[{$USERS[user].id}]" value="0"/>
        <input type="checkbox" onclick="storeLock({$USERS[user].id})" name="locked[{$USERS[user].id}]" value="1" id="locked[{$USERS[user].id}]" {if $USERS[user].is_locked}checked{/if} />
        <label for="locked[{$USERS[user].id}]"></label>
      </td>
      <td class="center">
        <input type="hidden" name="nofee[{$USERS[user].id}]" value="0"/>
        <input type="checkbox" onclick="storeFee({$USERS[user].id})" name="nofee[{$USERS[user].id}]" value="1" id="nofee[{$USERS[user].id}]" {if $USERS[user].no_fees}checked{/if} />
        <label for="nofee[{$USERS[user].id}]"></label>
      </td>
    </tr>
{sectionelse}
  <tr>
    <td colspan="10"></td>
  </tr>
{/section}
{/nocache}
  </tbody>
  <tfoot>
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
      <th class="center">No Fees</th>
    </tr>
  </tfoot>
</table>
{include file="global/block_footer.tpl"}
