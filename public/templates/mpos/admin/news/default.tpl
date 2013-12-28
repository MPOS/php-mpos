<article class="module width_full">
  <header><h3>News Posts</h3></header>
  <ul><li>News posts support the Markdown syntax</li></ul>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="add">
    <div class="module_content">
      <fieldset>
        <label>Header</label>
        <input size="30" type="text" name="data[header]" required />
      </fieldset>
      <fieldset>
        <label>Content</label>
        <textarea name="data[content]" rows="5" required></textarea>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Add" class="alt_btn">
      </div>
    </footer>
  </form>
</article>

{nocache}
{section name=news loop=$NEWS}
<article class="module width_full">
  <header><h3>{$NEWS[news].header} posted {$NEWS[news].time} by {$NEWS[news].author}</h3>
{if $NEWS[news].active == 0}<font size="2px"><font color="red"><b>inactive</b></font><br /><br />{/if}</header>
  <div class="module_content">{$NEWS[news].content}</div>
  <footer>
    <div class="submit_link">
      <a href='{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action=news_edit&id={$NEWS[news].id}'><i class="icon-wrench"></i></a>&nbsp;
      <a href='{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$NEWS[news].id}'><i class="icon-trash"></i></a>
    </div>
  </footer>
</article>
{/section}
{/nocache}
