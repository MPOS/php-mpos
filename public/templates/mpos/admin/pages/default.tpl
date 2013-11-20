<article class="module width_quarter">
  <header><h3>Select Page</h3></header>
  <form action="{$smarty.server.PHP_SELF}" method="GET">
    <div class="module_content">
      <input type="hidden" name="page" value="{$smarty.request.page}" />
      <input type="hidden" name="action" value="{$smarty.request.action}" />
      <fieldset>
        <label>Name</label>
        {html_options name="slug" options=$PAGES selected=$CURRENT_PAGE.slug}
      </fieldset>
      <fieldset>
        <label>Template</label>
        {html_options name="template" options=$TEMPLATES selected=$CURRENT_PAGE.template}
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Filter" class="alt_btn">
      </div>
    </footer>
  </form>
</article>

<article class="module width_3_quarter">
  <header><h3>
      {if $CURRENT_PAGE.template}
      Page '{$CURRENT_PAGE.name}' for '{$CURRENT_PAGE.template}' template
      {else}
      Common page '{$CURRENT_PAGE.name}'
      {/if}
  </h3></header>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page}">
    <input type="hidden" name="action" value="{$smarty.request.action}">
    <input type="hidden" name="slug" value="{$CURRENT_PAGE.slug}">
    <input type="hidden" name="template" value="{$CURRENT_PAGE.template}">
    <input type="hidden" name="do" value="save">
    <div class="module_content">
      <fieldset>
        <label>Active</label>
        <input type="hidden" name="active" value="0" />
        <input type="checkbox" name="active" value="1" id="active" {nocache}{if $CURRENT_PAGE.active}checked{/if}{/nocache} />
        <label for="active"></label>
      </fieldset>
      <fieldset>
        <label>Content</label>
        <textarea name="content" rows="15" type="text" required>{nocache}{$CURRENT_PAGE.content}{/nocache}</textarea>
      </fieldset>
    </div>
     <footer>
      <div class="submit_link">
        <input type="submit" value="Save" class="alt_btn">
      </div>
    </footer>
  </form>
</article>
