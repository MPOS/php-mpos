    <hgroup>
      <h1 class="site_title">{$GLOBAL.website.name|default:"Unknown Pool"}</h1>
      <h2 class="section_title">{if $smarty.request.action|escape|default:""}{$smarty.request.action|escape|capitalize}{else}{$smarty.request.page|escape|default:"home"|capitalize}{/if}</h2>
    </hgroup>
    {include file="login/small.tpl"}
