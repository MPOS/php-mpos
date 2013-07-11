{include file="global/block_header.tpl" BLOCK_HEADER="Admin Settings"}
<form method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page}" />
  <input type="hidden" name="action" value="{$smarty.request.action}" />
  <input type="hidden" name="do" value="save" />
  <table>
    <thead>
      <th class="left">Setting</th>
      <th class="center">Help</th>
      <th>Value</th>
    </thead>
    <tbody>
      <tr>
        <td class="left">Maintenance Mode</td>
        <td class="center"><span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Enable Maintenance Mode for mmcfe-ng. Only admins can login.'></span></td>
        <td>
          <select name="data[maintenance]">
            <option value="1">Yes</option>
            <option value="0"{nocache}{if !$MAINTENANCE} selected{/if}>{/nocache}No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class="left">Disable Registration</td>
        <td class="center"><span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Enable or disable new account registration. Can also be done via configuration option.'></span></td>
        <td>
          <select name="data[lock_registration]">
            <option value="1">Yes</option>
            <option value="0"{nocache}{if !$LOCKREGISTRATION} selected{/if}{/nocache}>No</option>
          </select>
        </td>
      </tr>
      <tr><td class="center" colspan="3"><input type="submit" value="Save" class="submit small" /></td></tr>
    </tbody>
  </table>
</form>
{include file="global/block_footer.tpl"}
