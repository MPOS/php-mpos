<article class="module width_full">
  <form method="POST">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
    <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
    <input type="hidden" name="do" value="save" />
    <header>
      <h3 class="tabs_involved">Settings</h3>
      <ul style="margin: 10px 20px 0px 0px;" class="tabs">
{foreach item=TAB from=array_keys($SETTINGS)}
        <li><a href="#{$TAB}">{$TAB|capitalize}</a></li>
{/foreach}
      </ul>
    </header>
    <div class="tab_container">
{foreach item=TAB from=array_keys($SETTINGS)}
      <div class="tab_content module_content" id="{$TAB}">
      <br />
{section name=setting loop=$SETTINGS.$TAB}
        <fieldset>
          <label>{$SETTINGS.$TAB[setting].display}</label>
          {if $SETTINGS.$TAB[setting].tooltip|default}<span style="font-size: 10px;">{$SETTINGS.$TAB[setting].tooltip}</span>{/if}
          {if $SETTINGS.$TAB[setting].type == 'select'}
            {html_options name="data[{$SETTINGS.$TAB[setting].name}]" options=$SETTINGS.$TAB[setting].options selected=$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}
          {else if $SETTINGS.$TAB[setting].type == 'text'}
            <input type="text" size="{$SETTINGS.$TAB[setting].size}" name="data[{$SETTINGS.$TAB[setting].name}]" value="{$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default|escape:"html"}" />
          {else if $SETTINGS.$TAB[setting].type == 'textarea'}
          	<textarea name="data[{$SETTINGS.$TAB[setting].name}]" cols="{$SETTINGS.$TAB[setting].size|default:"1"}" rows="{$SETTINGS.$TAB[setting].height|default:"1"}">{$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}</textarea>
          {else}
            Unknown option type: {$SETTINGS.$TAB[setting].type}
          {/if}
        </fieldset>
{/section}
      </div>
{/foreach}
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Save" class="alt_btn">
      </div>
    </footer>
  </form>
</article>
