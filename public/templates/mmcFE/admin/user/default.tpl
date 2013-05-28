{include file="global/block_header.tpl" BLOCK_HEADER="Query User Database"}
<form action="{$smarty.server.PHP_SELF}" method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="text" name="query" value="{$smarty.request.query|default:"%"}">
  <input type="submit" value="Query">
</form>
{include file="global/block_footer.tpl"}
