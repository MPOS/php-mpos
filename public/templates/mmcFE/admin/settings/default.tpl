{include file="global/block_header.tpl" BLOCK_HEADER="Admin Settings" BUTTONS=array_keys($SETTINGS)}
{foreach item=TAB from=array_keys($SETTINGS)}
<div class="block_content tab_content" id="{$TAB}" style="padding-left:30px;">
<form method="POST">
  <input type="hidden" name="page" value="{$smarty.request.page}" />
  <input type="hidden" name="action" value="{$smarty.request.action}" />
  <input type="hidden" name="do" value="save" />
  <table>
    <thead>
      <th class="left">Setting</th>
      <th class="center">Help</th>
      <th>Value</th>
    </thead>
    <tbody>
{section name=setting loop=$SETTINGS.$TAB}
      <tr>
        <td class="left">{$SETTINGS.$TAB[setting].display}</td>
        <td class="center">{if $SETTINGS.$TAB[setting].tooltip|default}<span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='{$SETTINGS.$TAB[setting].tooltip}.'></span>{/if}</td>
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
      <tr><td class="center" colspan="3"><input type="submit" value="Save" class="submit small" /></td></tr>
    </tbody>
  </table>
</form>
</div>
{/foreach}
{include file="global/block_footer.tpl"}
