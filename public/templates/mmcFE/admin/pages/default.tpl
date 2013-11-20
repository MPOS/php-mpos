{include file="global/block_header.tpl" BLOCK_HEADER="Select Page"}
<form action="{$smarty.server.PHP_SELF}" method="GET">
  Name
  {html_options name="slug" options=$PAGES selected=$CURRENT_PAGE.slug}
  Template
  {html_options name="template" options=$TEMPLATES selected=$CURRENT_PAGE.template}
  <input type="submit" class="submit small" value="Filter">
</form>
{include file="global/block_footer.tpl"}

{if $CURRENT_PAGE.template && print_r($CURRENT_PAGE)}
  {assign "BLOCK_HEADER" "Page '{$CURRENT_PAGE.name}' for '{$CURRENT_PAGE.template}' template"}
{else}
  {assign "BLOCK_HEADER" "Common page '{$CURRENT_PAGE.name}'"}
{/if}
{include file="global/block_header.tpl" BLOCK_HEADER=$BLOCK_HEADER}
<form method="POST" action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="hidden" name="slug" value="{$CURRENT_PAGE.slug}">
  <input type="hidden" name="template" value="{$CURRENT_PAGE.template}">
  <input type="hidden" name="do" value="save">
  <table width="80%">
    <tr>
      <th>
        Active
      </th>
      <td>
        <input type="checkbox" name="active" value="1" id="active" {nocache}{if $CURRENT_PAGE.active}checked{/if}{/nocache} />
        <label for="active"></label>
      </td>
    </tr>
    <tr>
      <th>
        Content
      </th>
      <td><textarea name="content" rows="500" type="text" required>{nocache}{$CURRENT_PAGE.content}{/nocache}</textarea></td>
    </tr>
  </table>
  <input type="submit" value="Save" class="submit small" />
</form>
{include file="global/block_footer.tpl"}
