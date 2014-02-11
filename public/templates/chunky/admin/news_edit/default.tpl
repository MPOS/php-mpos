<article class="module width_full">
  <header><h3>Edit news entry #{$NEWS.id}</h3></header>
<form method="POST" action="{$smarty.server.PHP_SELF}">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="id" value="{$NEWS.id}">
  <input type="hidden" name="do" value="save">
  <table class="tablesorter" cellspacing="0">
    <tr>
      <th>Active</th>
      <td>
        <input type="hidden" name="active" value="0" />
        <input type="checkbox" name="active" value="1" id="active" {nocache}{if $NEWS.active}checked{/if}{/nocache} />
        <label for="active"></label>
      </td>
    </tr>
    <tr>
      <th>Header</th>
      <td><input name="header" type="text" size="30" value="{nocache}{$NEWS.header}{/nocache}" required /></td>
    </tr>
    <tr>
      <th>Content</th>
      <td><textarea name="content" rows="15" cols="150" type="text" required>{nocache}{$NEWS.content}{/nocache}</textarea></td>
    </tr>
  </table>
   <footer>
    <div class="submit_link">
      <input type="submit" value="Save" class="alt_btn">
    </div>
  </footer> 
</form>
</article>
