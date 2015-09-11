  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-user-md fa-fw"></i> {t}Account Information{/t}</h4>
      </div>
      <div class="panel-body no-padding">
        <table class="table table-bordered table-hover table-striped">
          <tr>
            <td colspan="2">
    {if $GLOBAL.userdata.no_fees}
            {t}You are mining without any pool fees applied and{/t}
    {else if $GLOBAL.fees > 0}
            {t}You are mining at{/t} <font color="orange">{if $GLOBAL.fees < 0.0001}{$GLOBAL.fees|escape|number_format:"8"}{else}{$GLOBAL.fees|escape}{/if}%</font> pool fee and
    {else}
            {t}This pool does not apply fees and{/t}
    {/if}
    {if $GLOBAL.userdata.donate_percent > 0}
            {t}you donate{/t} <font color="green">{$GLOBAL.userdata.donate_percent|escape}%</font>.
    {else}
            {t}you are not{/t} <a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit">{t}donating{/t}</a>.
    {/if}
            </td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr><th colspan="2">{$GLOBAL.config.currency} {t}Account Balance{/t}</th></tr>
          </thead>
          <tbody>
            <tr>
              <th>{t}Confirmed{/t}</th>
              <th>
                <span class="label label-success pull-right bigfont" id="b-confirmed">{$GLOBAL.userdata.balance.confirmed|number_format:"6"}</span>
              </th>
            </tr>
            <tr>
              <th>{t}Unconfirmed{/t}</th>
              <th>
                <span class="label label-warning pull-right bigfont" id="b-unconfirmed">{$GLOBAL.userdata.balance.unconfirmed|number_format:"6"}</span>
              </th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
