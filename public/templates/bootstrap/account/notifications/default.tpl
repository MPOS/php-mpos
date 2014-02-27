<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        Notification Settings
      </div>
      <div class="panel-body">
      
        <form action="{$smarty.server.SCRIPT_NAME}" method="POST" role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}">
          <input type="hidden" name="action" value="{$smarty.request.action|escape}">
          <input type="hidden" name="do" value="save">
          <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />

          <table width="100%">
            <tr>
              <th align="left">Type</th>
              <th align="center">Active</th>
            </tr>
            <tr>
              <td align="left">IDLE Worker</td>
              <td>
                <label for="data[idle_worker]">
                <input type="hidden" name="data[idle_worker]" value="0" />
                <input type="checkbox" name="data[idle_worker]" id="data[idle_worker]" value="1"{nocache}{if $SETTINGS['idle_worker']|default:"0" == 1}checked{/if}{/nocache} />
                </label>
              </td>
            </tr>
      {if $DISABLE_BLOCKNOTIFICATIONS|default:"" != 1}
            <tr>
              <td align="left">New Blocks</td>
              <td>
                <label for="data[new_block]">
                <input type="hidden" name="data[new_block]" value="0" />
                <input type="checkbox" name="data[new_block]" id="data[new_block]" value="1"{nocache}{if $SETTINGS['new_block']|default:"0" == 1}checked{/if}{/nocache} />
                </label>
              </td>
            </tr>
      {/if}
            <tr>
              <td align="left">Payout</td>
              <td>
                <label for="data[payout]">
                <input type="hidden" name="data[payout]" value="0" />
                <input type="checkbox" name="data[payout]" id="data[payout]" value="1"{nocache}{if $SETTINGS['payout']|default:"0" == 1}checked{/if}{/nocache} />
                </label>
              </td>
            </tr>
            <tr>
              <td align="left">Successful Login</td>
              <td>
                <span class="toggle">
                <label for="data[success_login]">
                <input type="hidden" name="data[success_login]" value="0" />
                <input type="checkbox" class="ios-switch" name="data[success_login]" id="data[success_login]" value="1"{nocache}{if $SETTINGS['success_login']|default:"0" == 1}checked{/if}{/nocache} />
                <div class="switch"></div>
                </label>
                </span>
              </td>
            </tr>
          </table>
          <input type="submit" value="Update" class="btn btn-outline btn-success btn-lg btn-block">
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        Notification History
      </div>
      <div class="panel-body">
      
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th align="center">ID</th>
                <th align="center">Time</th>
                <th align="center">Type</th>
                <th align="center">Active</th>
              </tr>
            </thead>
            <tbody style="font-size:12px;">
{section notification $NOTIFICATIONS}
              <tr class="{cycle values="odd,even"}">
                <td align="center">{$NOTIFICATIONS[notification].id}</td>
                <td align="center">{$NOTIFICATIONS[notification].time}</td>
                <td align="center">
{if $NOTIFICATIONS[notification].type == new_block}New Block
{else if $NOTIFICATIONS[notification].type == auto_payout}Auto Payout
{else if $NOTIFICATIONS[notification].type == idle_worker}IDLE Worker
{else if $NOTIFICATIONS[notification].type == manual_payout}Manual Payout
{else if $NOTIFICATIONS[notification].type == success_login}Successful Login
{/if}
                </td>
                <td align="center">
                 <i class="fa-{if $NOTIFICATIONS[notification].active}check{else}times{/if} fa-fw"></i>
                </td>
              </tr>
{/section}
            <tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
