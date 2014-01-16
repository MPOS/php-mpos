    <hgroup>
      <h1 class="site_title">{$GLOBAL.website.name|default:"Unknown Pool"}</h1>
      <h2 class="section_title">{if $smarty.request.action|escape|default:""}{$smarty.request.action|escape|capitalize}{else}{$smarty.request.page|escape|default:"home"|capitalize}{/if}</h2>
    </hgroup>
    {if $GLOBAL.config.recaptcha_enabled|default:"0" != 1 || $GLOBAL.config.recaptcha_enabled_logins|default:"0" != 1}{nocache}{include file="login/small.tpl"}{/nocache}{/if}
