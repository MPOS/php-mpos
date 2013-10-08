{if $GLOBAL.userdata.id == $GLOBAL.userdata.team_owner}
<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="change" />
  <input type="hidden" name="team_id" value="{$GLOBAL.userdata.team_id|default}" />
  {html_options name=owner options=$TEAM_MEMBERS} <input type="submit" class="submit small" value="Change" />
</form>
{/if}
