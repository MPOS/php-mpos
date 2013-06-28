<!DOCTYPE html>
<html>
  <head>
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"  href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
    {if is_array($smarty.session.POPUP|default)}<script>{literal}$('#status').popup();{/literal}</script>{/if}
  </head>
  <body>
    <div data-role="page">
      <div data-role="header">
{include file="global/header.tpl"}
{include file="global/navigation.tpl"}
      </div><!-- /header -->
      {if is_array($smarty.session.POPUP|default)}
      <a href="#status" data-rel="popup"></a>
      <div data-role="popup" id="status" data-transition="pop">
        <p>Test</p>
      </div>
      {/if}
      <div data-role="content">
{include file="$PAGE/$ACTION/$CONTENT"}
      </div><!-- /content -->
      <div data-role="footer" data-position="fixed">
{include file="global/footer.tpl"}
      </div><!-- /footer -->
    </div><!-- /page -->
  </body>
</html>
