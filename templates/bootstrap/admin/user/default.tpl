<script language="javascript">
    function storeFee(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=fee&account_id=" + id + "&ctoken={$smarty.request.ctoken|escape}",
     });
    }
    function storeLock(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=lock&account_id=" + id + "&ctoken={$smarty.request.ctoken|escape}",
     });
    }
    function storeAdmin(id) {
      $.ajax({
       type: "POST",
       url: "{$smarty.server.SCRIPT_NAME}",
       data: "page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=admin&account_id=" + id + "&ctoken={$smarty.request.ctoken|escape}",
     });
    }
</script>

<div class="row">
  <form class="col-lg-12" role="form">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="query">
    <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-search fa-fw"></i> {t}User Search{/t}
      </div>
      <div class="panel-body">
        <ul class="pager">
          {if $smarty.request.start|default:"0" > 0} 
          <li class="previous" disabled>
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" - $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}">← {t}Prev{/t}</a>
          {else}
          <li class="previous disabled">
            <a href="#">← {t}Prev{/t}</a>
          {/if}
          </li>
          <li class="next">
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&start={$smarty.request.start|escape|default:"0" + $LIMIT}{if $FILTERS|default:""}{$FILTERS}{/if}">{t}Next{/t} →</a>
          </li>
        </ul>
        <div class="form-group">
          <label>{t}Account{/t}</label>
          <input size="20" class="form-control" type="text" name="filter[account]" value="{$smarty.request.filter.account|default:""}" />
        </div>
        <div class="form-group">
          <label>{t}E-Mail{/t}</label>
          <input size="20" class="form-control" type="text" name="filter[email]" value="{$smarty.request.filter.email|default:""}" />
        </div>
        <div class="form-group">
          <label>{t}Is Admin{/t}</label>
          {html_options class="form-control select-mini" name="filter[is_admin]" options=$ADMIN selected=$smarty.request.filter.is_admin|default:""}
        </div>
        <div class="form-group">
          <label>{t}Is Locked{/t}</label>
          {html_options class="form-control select-mini" name="filter[is_locked]" options=$LOCKED selected=$smarty.request.filter.is_locked|default:""}
        </div>
        <div class="form-group">
          <label>{t}No Fees{/t}</label>
          {html_options class="form-control select-mini" name="filter[no_fees]" options=$NOFEE selected=$smarty.request.filter.no_fees|default:""}
        </div>
      </div>
      <div class="panel-footer">
        <input type="submit" value="{t}Search!{/t}" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>
</div>


<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info fa-fw"></i> {t}User Information{/t}
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover {if $USERS}datatable{/if}">
            <thead>
              <tr>
                <th class="h6">{t}ID{/t}</th>
                <th class="h6">{t}Username{/t}</th>
                <th class="h6">{t}eMail{/t}</th>
                <th class="h6" style="padding-right:10px">{t}Shares{/t}</th>
                <th class="h6" style="padding-right:10px">{t}Hashrate{/t}</th>
{if $GLOBAL.config.payout_system != 'pps'}
                <th class="h6" style="padding-right:10px">{t}Est. Donation{/t}</th>
                <th class="h6" style="padding-right:10px">{t}Est. Payout{/t}</th>
{else}
                <th class="h6" colspan="2" style="padding-right:10px">{t}Est. 24 Hours{/t}</th>
{/if}
                <th class="h6" style="padding-right:10px">{t}Balance{/t}</th>
                <th class="h6" style="padding-right:10px">{t}Reg. Date{/t}</th>
                <th class="h6" style="padding-right:10px">{t}Last Login{/t}</th>
                <th class="h6">{t}Admin{/t}</th>
                <th class="h6">{t}Locked{/t}</th>
                <th class="h6">{t}No Fees{/t}</th>
              </tr>
            </thead>
            <tbody>
{nocache}
{section name=user loop=$USERS|default}
              <tr>
                <td>{$USERS[user].id}</td>
                <td><a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action=userdetails&id={$USERS[user].id}">{$USERS[user].username|escape}</a></td>
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
                <td>{$USERS[user].signup_timestamp|date_format:$GLOBAL.config.date}</td>
                <td>{$USERS[user].last_login|date_format:$GLOBAL.config.date}</td>
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
{/section}
{/nocache}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>