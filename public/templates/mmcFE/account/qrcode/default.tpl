{if !$GLOBAL.website.api.disabled}
{include file="global/block_header.tpl" BLOCK_HEADER="API String"}
<p>This code will allow you to import the full API string into your mobile application.</p>
<script type="text/javascript" src="{$PATH}/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
  {literal}
  //Wrap it within $(document).ready() to invoke the function after DOM loads.
  $(document).ready(function(){
    $('#qrcodeholder').qrcode({
      text    : "{/literal}|http{if $smarty.server.HTTPS eq '1'}s{/if}://{$smarty.server.SERVER_NAME}{$smarty.server.PHP_SELF}?page=api|{$GLOBAL.userdata.api_key}|{$GLOBAL.userdata.id}|{literal}",
      render    : "canvas",  // 'canvas' or 'table'. Default value is 'canvas'
      background : "#ffffff",
      foreground : "#000000",
      width : 250,
      height: 250 
    });
  });
  {/literal}
</script>
<div id="qrcodeholder"></div>
{include file="global/block_footer.tpl"}
{/if}
