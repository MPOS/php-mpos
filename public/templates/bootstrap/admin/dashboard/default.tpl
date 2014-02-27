{nocache}
<table width="100%">
  <tr>
    <td>
      {include file="admin/dashboard/mpos.tpl"}
    </td>
  </tr>
</table>
<table width="100%">
  <tr>
    <td>
      {include file="admin/dashboard/user.tpl"}
    </td>
  </tr>
</table>
<table width="100%">
  <tr>
    <td>
      {include file="admin/dashboard/registrations.tpl"}
      {if $GLOBAL.config.disable_invitations|default:"0" == 0}
      {include file="admin/dashboard/invitation.tpl"}
      {/if}
    </td>
  </tr>
</table>
{/nocache}
