{if !$GLOBAL.website.api.disabled}
<script type="text/javascript" src="{$GLOBALASSETS}/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
  {literal}
  $(document).ready(function(){
    $('#qrcodeholder').qrcode({
      text    : "{/literal}|http{if $smarty.server.HTTPS|default:"0" eq 'on'}s{/if}://{$smarty.server.SERVER_NAME}{if $smarty.server.SERVER_PORT != "443" && $smarty.server.SERVER_PORT != "80"}:{$smarty.server.SERVER_PORT}{/if}{$smarty.server.SCRIPT_NAME}?page=api|{$GLOBAL.userdata.api_key}|{$GLOBAL.userdata.id}|{$GLOBAL.config.currency|upper}|{literal}",
      render    : "canvas",  // 'canvas' or 'table'. Default value is 'canvas'
      background : "#ffffff",
      foreground : "#000000",
      width : 250,
      height: 250 
    });
  });
  {/literal}
</script>
<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          API String
        </div>
        <span class="tools">
          <i class="fa fa-qrcode"></i>
        </span>
      </div>
      <div class="widget-body text-center">
        <p>This code will allow you to import the full API string into your mobile application.</p>
        <div id="qrcodeholder"></div>
      </div>
    </div>
  </div>
</div>
{/if}
