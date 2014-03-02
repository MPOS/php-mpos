    <div class="breadcrumbs_container">
    {if $PAGE|@count == 0}{assign "PAGE" $smarty.request.page}{else}{assign "PAGE" "home"}{/if}
    {if $ACTION|@count == 0}{assign "ACTION" $smarty.request.action}{else}{assign "ACTION" ""}{/if}
      <article class="breadcrumbs"><a href="{$smarty.server.SCRIPT_NAME}">{$GLOBAL.website.name|default:"Unknown Pool"}</a> <div class="breadcrumb_divider"></div> <a class="{if ! $ACTION|default:""}current{/if}" {if $ACTION|default:""}href="{$smarty.server.SCRIPT_NAME}?page={$PAGE|default:"home"|escape|replace:'"':''}"{/if}>{$PAGE|escape|default:"Home"|capitalize|escape|replace:'"':''}</a>{if $ACTION|default:""} <div class="breadcrumb_divider"></div> <a class="current">{$ACTION|escape|capitalize|escape|replace:'"':''}</a>{/if}</article>
    </div>
