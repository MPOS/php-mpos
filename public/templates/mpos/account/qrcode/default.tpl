{assign "loaded_jq" false}
{if !$GLOBAL.website.api.disabled}
<script type="text/javascript" src="{$PATH}/js/jquery.qrcode.min.js"></script>
{assign "loaded_jq" true}
<script type="text/javascript">
  {literal}
  $(document).ready(function(){
    $('#qrcodeholder').qrcode({
      text    : "{/literal}|http{if $smarty.server.HTTPS|default:"0" eq 'on'}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME}?page=api|{$GLOBAL.userdata.api_key}|{$GLOBAL.userdata.id}|{literal}",
      render    : "canvas",  // 'canvas' or 'table'. Default value is 'canvas'
      background : "#ffffff",
      foreground : "#000000",
      width : 250,
      height: 250 
    });
  });
  {/literal}
</script>
<article class="module width_quarter">
  <header><h3>API String</h3></header>
  <div class="module_content">
    <p>This code will allow you to import the full API string into your mobile application.</p>
    <div id="qrcodeholder"></div>
  </div>
</article>
{/if}

{if $GLOBAL.twofactor.mode == "gauth" && $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.login}
{if !$loaded_jq}<script type="text/javascript" src="{$PATH}/js/jquery.qrcode.min.js"></script>{/if}
<script type="text/javascript">
  {literal}
  $(document).ready(function(){
    $('#qrcodeholder_ga').qrcode({
      text    : "{/literal}{$GAUTH_URL}{literal}",
      render    : "canvas",  // 'canvas' or 'table'. Default value is 'canvas'
      background : "#ffffff",
      foreground : "#000000",
      width : 250,
      height: 250 
    });
  });
  {/literal}
</script>
<article class="module width_quarter">
  <header><h3>Google Authenticator</h3></header>
  <div class="module_content">
    <p>This code will allow you to login using your Google Authenticator.</p>
    <div id="qrcodeholder_ga"></div>
  </div>
</article>
{/if}
