{include file="global/block_header.tpl" ALIGN="left" BLOCK_HEADER="Notification Settings"}
<form action="{$smarty.server.PHP_SELF}" method="POST">
<input type="hidden" name="page" value="{$smarty.request.page}">
<input type="hidden" name="action" value="{$smarty.request.action}">
<input type="hidden" name="do" value="save">
<table width="100%">
  <tr>
    <th class="left">Type</th>
    <th class="center">Active</th>
  </tr>
  <tr>
    <td class="left">IDLE Worker</td>
    <td class="center">
      <input type="hidden" name="data[idle_worker]" value="0" />
      <input type="checkbox" name="data[idle_worker]" value="1"{if $SETTINGS['idle_worker']}checked{/if} />
    </td>
  </tr>
  <tr>
    <td class="left">New Blocks</td>
    <td class="center">
      <input type="hidden" name="data[new_block]" value="0" />
      <input type="checkbox" name="data[new_block]" value="1"{if $SETTINGS['new_block']}checked{/if} />
    </td>
  </tr>
  <tr>
    <td class="left">Auto Payout</td>
    <td class="center">
      <input type="hidden" name="data[auto_payout]" value="0" />
      <input type="checkbox" name="data[auto_payout]" value="1"{if $SETTINGS['auto_payout']}checked{/if} />
    </td>
  </tr>
  <tr>
    <td colspan="2" class="center">
      <input type="submit" class="submit small" value="Update">
    </td>
  </tr>
</table>
</form>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" ALIGN="right" BLOCK_HEADER="Notification History"}
  <center>
    {include file="global/pagination.tpl"}
    <table width="100%" class="pagesort">
      <thead style="font-size:13px;">
        <tr>
          <th class="center" style="cursor: pointer;">ID</th>
          <th class="center" style="cursor: pointer;">Time</th>
          <th class="center" style="cursor: pointer;">Type</th>
          <th class="center" style="cursor: pointer;">Active</th>
        </tr>
      </thead>
      <tbody style="font-size:12px;">
{section notification $NOTIFICATIONS}
        <tr class="{cycle values="odd,even"}">
          <td class="center">{$NOTIFICATIONS[notification].id}</td>
          <td class="center">{$NOTIFICATIONS[notification].time}</td>
          <td class="center">{$NOTIFICATIONS[notification].type}</td>
          <td class="center">
            <img src="{$PATH}/images/{if $NOTIFICATIONS[notification].active}success{else}error{/if}.gif" />
          </td>
        </tr>
{/section}
      </tbody>
    </table>
  </center>
{include file="global/block_footer.tpl"}
