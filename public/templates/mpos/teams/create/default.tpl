<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
  <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
  <input type="hidden" name="do" value="create" />
  <table width="100%">
    <tbody>
      <tr>
        <th>Name</th>
        <td><input type="text" size="25" name="team[name]" value="{$smarty.request.team.name|default:""|escape}" /></td>
      </tr>
      <tr>
        <th>Slogan</th>
        <td><input type="text" size="25" name="team[slogan]" value="{$smarty.request.team.slogan|default:""|escape}" /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" class="submit small" value="Create" /></td>
      </tr>
    </tbody>
  </table>
</form>
