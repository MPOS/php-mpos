<!DOCTYPE html>
<html>
  <head>
    <title>{$GLOBAL.website.title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"  href="{$PATH}/css/jquery.mobile-1.3.2.min.css" />
    <script src="{$PATH}/js/jquery-1.9.1.min.js"></script>
    <script src="{$PATH}/js/jquery.mobile-1.3.2.min.js"></script>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <script>
{literal}
      $( document ).on( "pageinit", "#mpos-page", function() {
        $( document ).on( "swipeleft swiperight", "#mpos-page", function( e ) {
          // We check if there is no open panel on the page because otherwise
          // a swipe to close the left panel would also open the right panel (and v.v.).
          // We do this by checking the data that the framework stores on the page element (panel: open).
          if ( $.mobile.activePage.jqmData( "panel" ) !== "open" ) {
            if ( e.type === "swipeleft" ) {
              $( "#right-sidebar" ).panel( "open" );
            } else if ( e.type === "swiperight" ) {
              $( "#left-sidebar" ).panel( "open" );
            }
          }
        });
      });
{/literal}
    </script>
{/if}
  </head>
  <body>
    <div data-role="page" id="mpos-page" data-url="mpos-page">
{if $smarty.session.AUTHENTICATED|default:"0" == 1}
{assign var=payout_system value=$GLOBAL.config.payout_system}
      <div data-role="panel" id="left-sidebar" data-theme="a">
          {include file="global/sidebar_$payout_system.tpl"}
          <a href="#" data-rel="close" data-role="button" data-mini="true" data-inline="true" data-icon="delete" data-iconpos="right">Close</a>
      </div><!-- /panel -->
{/if}
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
{if file_exists($smarty.current_dir|cat:"/$PAGE/$ACTION/$CONTENT")}{include file="$PAGE/$ACTION/$CONTENT"}{else}Missing template for this page{/if}
      </div><!-- /content -->
      <div data-role="footer" data-position="fixed">
{include file="global/footer.tpl"}
      </div><!-- /footer -->
    </div><!-- /page -->
  </body>
</html>
