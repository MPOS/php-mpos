{include file="global/block_header.tpl" BLOCK_HEADER="Edit news entry #{$NEWS.id}"}
<form method="POST" action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page}">
  <input type="hidden" name="action" value="{$smarty.request.action}">
  <input type="hidden" name="id" value="{$NEWS.id}">
  <input type="hidden" name="do" value="save">
  <table width="80%">
    <tr>
      <th>
        Active
      </th>
      <td>
        <input type="checkbox" name="active" value="1" id="active" {nocache}{if $NEWS.active}checked{/if}{/nocache} />
        <label for="active"></label>
      </td>
    </tr>
    <tr>
      <th>
        Header
      </th>
      <td><input name="header" type="text" size="30" value="{nocache}{$NEWS.header}{/nocache}" required /></td>
    </tr>
    <tr>
      <th>
        Content
      </th>
      <td><textarea name="content" rows="500" type="text" required>{nocache}{$NEWS.content}{/nocache}</textarea></td>
    </tr>
  </table>
  <input type="submit" value="Save" class="submit small" />
</form>
{include file="global/block_footer.tpl"}
