<script language="javascript">
    function storeFee(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=fee&account_id=" + id,
     });
    }
    function storeLock(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=lock&account_id=" + id,
     });
    }
    function storeAdmin(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=admin&account_id=" + id,
     });
    }
</script>

<article class="module width_full">
  <header><h3>User Search</h3></header>
  <div class="module_content">
  <form action="{$smarty.server.SCRIPT_NAME}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
    <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
    <input type="hidden" name="do" value="query" />
    <table cellspacing="0" class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
{if $smarty.request.start|default:"0" > 0}
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-left-open"></i> Previous 30</a>
{else}
          <i class="icon-left-open"></i>
{/if}
        </td>
        <td align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}">Next 30 <i class="icon-right-open"></i></a>
        </td>
    </tbody>
  </table>
    <fieldset>
      <label>Account</label>
      <input size="20" type="text" name="filter[account]" value="{$smarty.request.filter.account|default:""}" />
    </fieldset>
    <fieldset>
      <label>E-Mail</label>
      <input size="20" type="text" name="filter[email]" value="{$smarty.request.filter.email|default:""}" />
    </fieldset>
    <fieldset>
      <label>Is Admin</label>
      {html_options name="filter[is_admin]" options=$ADMIN selected=$smarty.request.filter.is_admin|default:""}
    </fieldset>
    <fieldset>
      <label>Is Locked</label>
      {html_options name="filter[is_locked]" options=$LOCKED selected=$smarty.request.filter.is_locked|default:""}
    </fieldset>
    <fieldset>
      <label>No Fees</label>
      {html_options name="filter[no_fees]" options=$NOFEE selected=$smarty.request.filter.no_fees|default:""}
    </fieldset>
    <ul>
      <li>Note: Text search fields support '%' as wildcard.</li>
    </ul>
  </div>
  <footer>
    <div class="submit_link">
      <input type="submit" value="Search" class="alt_btn">
    </div>
  </footer>
</form>
</article>

<article class="module width_full">
  <header>
    <h3>User Information</h3>
  </header>
<table cellspacing="0" width="100%" class="tablesorter">
  <thead>
    <tr>
      <th align="center">ID</th>
      <th align="left">Username</th>
      <th align="left">E-Mail</th>
      <th align="right" style="padding-right:10px">Shares</th>
      <th align="right" style="padding-right:10px">Hashrate</th>
{if $GLOBAL.config.payout_system != 'pps'}
      <th align="right" style="padding-right:10px">Est. Donation</th>
      <th align="right" style="padding-right:10px">Est. Payout</th>
{else}
      <th align="right" colspan="2" style="padding-right:10px">Est. 24 Hours</th>
{/if}
      <th align="right" style="padding-right:10px">Balance</th>
      <th align="right" style="padding-right:10px">Reg. Date</th>
      <th align="right" style="padding-right:10px">Last Login</th>
      <th align="center">Admin</th>
      <th align="center">Locked</th>
      <th align="center">No Fees</th>
    </tr>
  </thead>
  <tbody>
{nocache}
{section name=user loop=$USERS|default}
    <tr>
      <td align="center">{$USERS[user].id}</td>
      <td align="left">{$USERS[user].username|escape}</td>
      <td align="left">{$USERS[user].email|escape}</td>
      <td align="right">{$USERS[user].shares.valid}</td>
      <td align="right">{$USERS[user].hashrate}</td>
{if $GLOBAL.config.payout_system != 'pps'}
      <td align="right">{$USERS[user].estimates.donation|number_format:"8"}</td>
      <td align="right">{$USERS[user].estimates.payout|number_format:"8"}</td>
{else}
      <td align="right" colspan="2">{$USERS[user].estimates.hours24|number_format:"8"}</td>
{/if}
      <td align="right">{$USERS[user].balance|number_format:"8"}</td>
      <td align="right">{$USERS[user].signup_timestamp|date_format:"%d/%m %H:%M:%S"}</td>
      <td align="right">{$USERS[user].last_login|date_format:"%d/%m %H:%M:%S"}</td>
      <td align="center">
        <input type="hidden" name="admin[{$USERS[user].id}]" value="0"/>
        <input type="checkbox" onclick="storeAdmin({$USERS[user].id})" name="admin[{$USERS[user].id}]" value="1" id="admin[{$USERS[user].id}]" {if $USERS[user].is_admin}checked{/if} />
        <label for="admin[{$USERS[user].id}]"></label>
      </td>
      <td align="center">
        <input type="hidden" name="locked[{$USERS[user].id}]" value="0"/>
        <input type="checkbox" onclick="storeLock({$USERS[user].id})" name="locked[{$USERS[user].id}]" value="1" id="locked[{$USERS[user].id}]" {if $USERS[user].is_locked}checked{/if} />
        <label for="locked[{$USERS[user].id}]"></label>
      </td>
      <td align="center">
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
</table>
</article>
