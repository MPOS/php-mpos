<article class="module width_full">
  <form method="POST">
    <input type="hidden" name="page" value="{$smarty.request.page}" />
    <input type="hidden" name="action" value="{$smarty.request.action}" />
    <input type="hidden" name="do" value="save" />
    <header>
      <h3 class="tabs_involved">Settings</h3>
      <ul class="tabs">
{foreach item=TAB from=array_keys($SETTINGS)}
        <li><a href="#{$TAB}">{$TAB|capitalize}</a></li>
{/foreach}
      </ul>
    </header>
    <div class="tab_container">
{foreach item=TAB from=array_keys($SETTINGS)}
      <div class="tab_content" id="{$TAB}">
        <table class="tablesorter" cellspacing="0">
          <thead>
            <th align="left" width="15%">Setting</th>
            <th align="center" width="50px">Help</th>
            <th>Value</th>
          </thead>
          <tbody>
{section name=setting loop=$SETTINGS.$TAB}
            <tr>
              <td align="left">{$SETTINGS.$TAB[setting].display}</td>
              <td align="center">{if $SETTINGS.$TAB[setting].tooltip|default}<span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='{$SETTINGS.$TAB[setting].tooltip}.'></span>{/if}</td>
              <td>
          {if $SETTINGS.$TAB[setting].type == 'select'}
                {html_options name="data[{$SETTINGS.$TAB[setting].name}]" options=$SETTINGS.$TAB[setting].options selected=$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}
          {else if $SETTINGS.$TAB[setting].type == 'text'}
                <input type="text" size="{$SETTINGS.$TAB[setting].size}" name="data[{$SETTINGS.$TAB[setting].name}]" value="{$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}" />
          {else}
                Unknown option type: {$SETTINGS.$TAB[setting].type}
          {/if}
              </td>
            </tr>
{/section}
          </tbody>
        </table>
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
