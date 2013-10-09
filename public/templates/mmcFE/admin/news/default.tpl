{include file="global/block_header.tpl" BLOCK_HEADER="News Posts"}
{include file="global/block_header.tpl" BLOCK_HEADER="Add News Post"}
<form method="POST" action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="hidden" name="do" value="add">
  <table width="80%">
    <tr>
      <th>Header</th>
      <td><input size="30" type="text" name="data[header]" required /></td>
    </tr>
    <tr>
      <th>Content</th>
      <td><textarea name="data[content]" required></textarea></td>
    </tr>
    <tr><td class="center" colspan="2"><input type="submit" value="Add" class="submit small"></td></tr>
  </table>
</form>
{include file="global/block_footer.tpl"}
{nocache}
{section name=news loop=$NEWS}
{include
  file="global/block_header.tpl"
  BLOCK_HEADER="{$NEWS[news].header} posted {$NEWS[news].time} by {$NEWS[news].author}"
  BUTTONS="
    <a href='{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action=news_edit&id={$NEWS[news].id}'>Edit</a>&nbsp;
    <a href='{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&do=delete&id={$NEWS[news].id}'>Delete</a>
  "
}
{if $NEWS[news].active == 0}<font size="2px">This post is <font color="red"><b>inactive</b></font><br /><br />{/if}
{$NEWS[news].content}
<form method="POST" action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="news_edit">
  <input type="hidden" name="id" value="{$NEWS[news].id}">
</form>
{include file="global/block_footer.tpl"}
{/section}
{/nocache}
{include file="global/block_footer.tpl"}
