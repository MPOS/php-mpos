{nocache}
<article class="module width_quarter">
  <header><h3><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">Invitations</a></h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Total</th>
        <th align="center">Activated</th>
        <th align="center">Outstanding</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="center">{$INVITATION_INFO.total}</td>
        <td align="center">{$INVITATION_INFO.activated}</td>
        <td align="center">{$INVITATION_INFO.outstanding}</td>
      </tr>
    </tbody>
  </table>
</article>
{/nocache}