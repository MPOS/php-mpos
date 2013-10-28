<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="hidden" name="do" value="sendInvitation">
  <article class="module width_quarter">
    <header><h3>Einladungen</h3></header>
    <div class="module_content">
      <fieldset>
        <label>E-Mail</label>
        <input type="text" name="data[email]" value="{$smarty.request.data.email|escape|default:""}" size="30" />
      </fieldset>
      <fieldset>
        <label>Nachricht</label>
        <textarea name="data[message]" rows="5">{$smarty.request.data.message|escape|default:"Bitte nimm meine Einladung in diesen fantastischen Pool an."}</textarea>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Verschicken" class="alt_btn">
      </div>
    </footer>
  </article>
</form>

<article class="module width_3_quarter">
  <header><h3>Letzte Einladungen</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead style="font-size:13px;">
      <tr>
        <th>E-Mail</th>
        <th align="center">Gesendet</th>
        <th align="center">Aktiviert</th>
      </tr>
    </thead>
    <tbody>
{section name=invite loop=$INVITATIONS}
      <tr>
        <td>{$INVITATIONS[invite].email}</td>
        <td align="center">{$INVITATIONS[invite].time|date_format:"%d/%m/%Y %H:%M:%S"}</td>
        <td align="center"><i class="icon-{if $INVITATIONS[invite].is_activated}ok{else}cancel{/if}"></i></td>
      </tr>
{/section}
    <tbody>
  </table>
</article>
