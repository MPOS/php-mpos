<form action="{$smarty.server.SCRIPT_NAME}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="save">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <article class="module width_quarter">
    <header>
      <h3>Notification Settings</h3>
    </header>
    <div class="module_content">
    <table width="100%">
      <tr>
        <th align="left">Type</th>
        <th align="center">Active</th>
      </tr>
      <tr>
        <td align="left">IDLE Worker</td>
        <td>
          <span class="toggle">
          <label for="data[idle_worker]">
          <input type="hidden" name="data[idle_worker]" value="0" />
          <input type="checkbox" class="ios-switch" name="data[idle_worker]" id="data[idle_worker]" value="1"{nocache}{if $SETTINGS['idle_worker']|default:"0" == 1}checked{/if}{/nocache} />
          <div class="switch"></div>
          </label>
          </span>
        </td>
      </tr>
      {if $DISABLE_BLOCKNOTIFICATIONS|default:"" != 1}
      <tr>
        <td align="left">New Blocks</td>
        <td>
          <span class="toggle">
          <label for="data[new_block]">
          <input type="hidden" name="data[new_block]" value="0" />
          <input type="checkbox" class="ios-switch" name="data[new_block]" id="data[new_block]" value="1"{nocache}{if $SETTINGS['new_block']|default:"0" == 1}checked{/if}{/nocache} />
          <div class="switch"></div>
          </label>
          </span>
        </td>
      </tr>
      {/if}
      <tr>
        <td align="left">Payout</td>
        <td>
          <span class="toggle">
          <label for="data[payout]">
          <input type="hidden" name="data[payout]" value="0" />
          <input type="checkbox" class="ios-switch" name="data[payout]" id="data[payout]" value="1"{nocache}{if $SETTINGS['payout']|default:"0" == 1}checked{/if}{/nocache} />
          <div class="switch"></div>
          </label>
          </span>
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
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Update" class="alt_btn">
      </div>
    </footer>
  </article>
</form>

<article class="module width_3_quarter">
  <header>
      <h3>Notification History</h3>
      <div class="submit_link">{include file="global/pagination.tpl"}</div>
  </header>
  <table width="100%" class="tablesorterpager" cellspacing="0">
    <thead style="font-size:13px;">
      <tr>
        <th align="center" style="cursor: pointer;">ID</th>
        <th align="center" style="cursor: pointer;">Time</th>
        <th align="center" style="cursor: pointer;">Type</th>
        <th align="center" style="cursor: pointer;">Active</th>
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
          <i class="icon-{if $NOTIFICATIONS[notification].active}ok{else}cancel{/if}"></i>
        </td>
      </tr>
{/section}
    </tbody>
  </table>
</article>
