    <hgroup>
      <h1 class="site_title">{$GLOBAL.website.name}</h1>
      <h2 class="section_title">{if $smarty.request.action|default:""}{$smarty.request.action|capitalize}{else}{$smarty.request.page|default:"home"|capitalize}{/if}</h2>
    </hgroup>
    {include file="login/small.tpl"}
