<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$GLOBAL.website.title} - {$smarty.request.page|escape|default:"home"|capitalize}</title>
  
  <!--[if lt IE 9]>
  <link rel="stylesheet" href="{$PATH}/css/ie.css" type="text/css" media="screen" />
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!--[if IE]><script type="text/javascript" src="{$PATH}/js/excanvas.js"></script><![endif]-->
  {if $GLOBAL.statistics.analytics.enabled}
  {$GLOBAL.statistics.analytics.code nofilter}
  {/if}
  <link href="{$GLOBALASSETS}/css/bootstrap.min.css" rel="stylesheet">
  <link href="{$GLOBALASSETS}/css/bootstrap-switch.min.css" rel="stylesheet">
  <link href="{$GLOBALASSETS}/css/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
  <link href="{$PATH}/css/plugins/uikit/notify.min.css" rel="stylesheet">
  <link href="{$PATH}/css/new.css" rel="stylesheet">
  <link href="{$GLOBALASSETS}/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="{$GLOBALASSETS}/css/plugins/morris/morris-0.5.1.css" rel="stylesheet">
  <link href="{$GLOBALASSETS}/css/plugins/sparkline/sparklines.css" rel="stylesheet">
  {if $GLOBAL.config.website_design|default:"default" != "default"}
  <link href="{$PATH}/css/design/{$GLOBAL.config.website_design}.css" rel="stylesheet">
  {/if}
  
  <script src="{$GLOBALASSETS}/js/jquery.min.js"></script>
  <script src="{$GLOBALASSETS}/js/jquery.cookie.js"></script>
  <script src="{$GLOBALASSETS}/js/jquery.md5.js"></script>
  <script src="{$GLOBALASSETS}/js/bootstrap.min.js"></script>
  <script src="{$GLOBALASSETS}/js/bootstrap-switch.min.js"></script>
  <script src="{$PATH}/js/plugins/uikit/uikit.min.js"></script>
  <script src="{$PATH}/js/plugins/uikit/notify.min.js"></script>
  <script src="{$GLOBALASSETS}/js/plugins/dataTables/jquery.dataTables.js"></script>
  <script src="{$GLOBALASSETS}/js/plugins/dataTables/dataTables.bootstrap.js"></script>
  <script src="{$GLOBALASSETS}/js/plugins/raphael-2.1.2.min.js"></script>
  <script src="{$GLOBALASSETS}/js/plugins/morris/morris-0.5.1.min.js"></script>
  <script src="{$GLOBALASSETS}/js/plugins/sparkline/jquery.sparkline.min.js"></script>
  <script src="{$PATH}/js/mpos.js"></script>
</head>
<body>
  {include file="global/header.tpl"}
  <div class="dashboard-container">
    <div class="container">

      {include file="global/navigation.tpl"}

      <div id="page-content" class="dashboard-wrapper">
      {nocache}
      {if is_array($smarty.session.POPUP|default)}
        {section popup $smarty.session.POPUP}
          {literal}
          <script type="text/javascript">
            UIkit.notify('{/literal}{$smarty.session.POPUP[popup].CONTENT nofilter}{literal}', {
              pos: 'top-center',
              notifyhandle: '{/literal}{$smarty.session.POPUP[popup].ID|default:"static"}{literal}',
              {/literal}{if $GLOBAL.website.notificationshide != 0}{literal}timeout: 0,{/literal}{else}{literal}timeout: 5000,{/literal}{/if}
              {if $smarty.session.POPUP[popup].TYPE|default:"alert alert-info" == "alert alert-info"}{literal}status: 'primary',{/literal}
              {elseif $smarty.session.POPUP[popup].TYPE|default:"alert alert-info" == "alert alert-warning"}{literal}status: 'warning',{/literal}
              {elseif $smarty.session.POPUP[popup].TYPE|default:"alert alert-info" == "alert alert-danger"}{literal}status: 'danger',{/literal}
              {elseif $smarty.session.POPUP[popup].TYPE|default:"alert alert-info" == "alert alert-success"}{literal}status: 'success',{/literal}{/if}{literal}
            });
          </script>
          {/literal}
        <!--
        <div class="{$smarty.session.POPUP[popup].TYPE|default:"alert alert-info"} {if $smarty.session.POPUP[popup].ID|default:"static" == "static" AND $GLOBAL.website.notificationshide == 1}autohide{/if}" id="{$smarty.session.POPUP[popup].ID|default:"static"}">
          {if $smarty.session.POPUP[popup].DISMISS|default:"no" == "yes"}
          <button id="{$smarty.session.POPUP[popup].ID|default:"static"}" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {/if}
        </div>
        -->
        {/section}
      {/if}
      {/nocache}
      {if $CONTENT != "empty" && $CONTENT != ""}
        {if file_exists($smarty.current_dir|cat:"/$PAGE/$ACTION/$CONTENT")}
          {include file="$PAGE/$ACTION/$CONTENT"}
        {else}
          Missing template for this page
        {/if}
      {/if}
      </div>
      {include file="global/footer.tpl"}
    </div>
  </div>
</body>
</html>
