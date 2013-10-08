{if $GLOBAL.userdata.team_id}
<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
  <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
  <input type="hidden" name="do" value="leave" />
  <input type="hidden" name="team[id]" />
  {$GLOBAL.userdata.team_name} : <input type="submit" class="submit small" value="leave" />
</form>
{/if}
