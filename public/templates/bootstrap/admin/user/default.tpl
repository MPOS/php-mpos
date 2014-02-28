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

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        User Search
      </div>
      <form action="{$smarty.server.SCRIPT_NAME}" role="form">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
        <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
        <input type="hidden" name="do" value="query" />
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td>
                    {if $smarty.request.start|default:"0" > 0}
                    <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}"><i class="icon-left-open"></i> Previous 30</a>
                    {else}
                    <i class="icon-left-open"></i>
                    {/if}
                  </td>
                  <td>
                    <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}">Next 30 <i class="icon-right-open"></i></a>
                  </td>
                </tr>
            </thead>
          </table>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <tbody>
              <div class="form-group">
                <label>Account</label>
                <input size="20" class="form-control" type="text" name="filter[account]" value="{$smarty.request.filter.account|default:""}" />
              </div>
              <div class="form-group">
                <label>E-Mail</label>
                <input size="20" class="form-control" type="text" name="filter[email]" value="{$smarty.request.filter.email|default:""}" />
              </div>
              <div class="form-group">
                <label>Is Admin</label>
                {html_options class="form-control" name="filter[is_admin]" options=$ADMIN selected=$smarty.request.filter.is_admin|default:""}
              </div>
              <div class="form-group">
                <label>Is Locked</label>
                {html_options class="form-control" name="filter[is_locked]" options=$LOCKED selected=$smarty.request.filter.is_locked|default:""}
              </div>
              <div class="form-group">
                <label>No Fees</label>
                {html_options class="form-control" name="filter[no_fees]" options=$NOFEE selected=$smarty.request.filter.no_fees|default:""}
              </div>
              <ul>
                <li>Note: Text search fields support '%' as wildcard.</li>
              </ul>
            </tbody>
          </table>
          <input type="submit" value="Search" class="btn btn-outline btn-success btn-lg btn-block">
        </div>
      </form>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        User Information
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>E-Mail</th>
                <th style="padding-right:10px">Shares</th>
                <th style="padding-right:10px">Hashrate</th>
{if $GLOBAL.config.payout_system != 'pps'}
                <th style="padding-right:10px">Est. Donation</th>
                <th style="padding-right:10px">Est. Payout</th>
{else}
                <th colspan="2" style="padding-right:10px">Est. 24 Hours</th>
{/if}
                <th style="padding-right:10px">Balance</th>
                <th style="padding-right:10px">Reg. Date</th>
                <th style="padding-right:10px">Last Login</th>
                <th>Admin</th>
                <th>Locked</th>
                <th>No Fees</th>
              </tr>
            </thead>
            <tbody>
{nocache}
{section name=user loop=$USERS|default}
              <tr>
                <td>{$USERS[user].id}</td>
                <td>{$USERS[user].username|escape}</td>
                <td>{$USERS[user].email|escape}</td>
                <td>{$USERS[user].shares.valid}</td>
                <td>{$USERS[user].hashrate}</td>
{if $GLOBAL.config.payout_system != 'pps'}
                <td>{$USERS[user].estimates.donation|number_format:"8"}</td>
                <td>{$USERS[user].estimates.payout|number_format:"8"}</td>
{else}
                <td colspan="2">{$USERS[user].estimates.hours24|number_format:"8"}</td>
{/if}
                <td>{$USERS[user].balance|number_format:"8"}</td>
                <td>{$USERS[user].signup_timestamp|date_format:"%d/%m %H:%M:%S"}</td>
                <td>{$USERS[user].last_login|date_format:"%d/%m %H:%M:%S"}</td>
                <td>
                  <input type="hidden" name="admin[{$USERS[user].id}]" value="0"/>
                  <input type="checkbox" onclick="storeAdmin({$USERS[user].id})" name="admin[{$USERS[user].id}]" value="1" id="admin[{$USERS[user].id}]" {if $USERS[user].is_admin}checked{/if} />
                  <label for="admin[{$USERS[user].id}]"></label>
                </td>
                <td>
                  <input type="hidden" name="locked[{$USERS[user].id}]" value="0"/>
                  <input type="checkbox" onclick="storeLock({$USERS[user].id})" name="locked[{$USERS[user].id}]" value="1" id="locked[{$USERS[user].id}]" {if $USERS[user].is_locked}checked{/if} />
                  <label for="locked[{$USERS[user].id}]"></label>
                </td>
                <td>
                  <input type="hidden" name="nofee[{$USERS[user].id}]" value="0"/>
                  <input type="checkbox" onclick="storeFee({$USERS[user].id})" name="nofee[{$USERS[user].id}]" value="1" id="nofee[{$USERS[user].id}]" {if $USERS[user].no_fees}checked{/if} />
                  <label for="nofee[{$USERS[user].id}]"></label>
                </td>
              </tr>
{sectionelse}
              <tr>
                <td colspan="13"></td>
              </tr>
{/section}
{/nocache}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
