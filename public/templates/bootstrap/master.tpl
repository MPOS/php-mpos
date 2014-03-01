<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$GLOBAL.website.title} I {$smarty.request.page|escape|default:"home"|capitalize}</title>
	
  <!--[if lt IE 9]>
  <link rel="stylesheet" href="{$PATH}/css/ie.css" type="text/css" media="screen" />
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script type="text/javascript" src="{$PATH}/js/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="{$PATH}/js/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="{$PATH}/js/jquery.equalHeight.js"></script>
  <script type="text/javascript" src="{$PATH}/../global/js/number_format.js"></script>
  <!--[if IE]><script type="text/javascript" src="{$PATH}/js/excanvas.js"></script><![endif]-->
  {literal}<script>
    var zxcvbnPath = "{/literal}{$PATH}{literal}/js/zxcvbn/zxcvbn.js";
  </script>{/literal}
  <script type="text/javascript" src="{$PATH}/js/pwcheck.js"></script>
  {if $GLOBAL.statistics.analytics.enabled}
  {$GLOBAL.statistics.analytics.code nofilter}
  {/if}
  <link href="{$PATH}/css/bootstrap.min.css" rel="stylesheet">
  <link href="{$PATH}/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="{$PATH}/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
  <link href="{$PATH}/css/plugins/timeline/timeline.css" rel="stylesheet">
  <link href="{$PATH}/css/mpos.css" rel="stylesheet">
</head>
<body>

    <div id="wrapper">

      {include file="global/header.tpl"}
      {include file="global/navigation.tpl"}

      <div id="page-wrapper"><br />
      {nocache}
      {if is_array($smarty.session.POPUP|default)}
        {section popup $smarty.session.POPUP}
        <div class="{$smarty.session.POPUP[popup].TYPE|default:"alert alert-info"}">{$smarty.session.POPUP[popup].CONTENT nofilter}</div>
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
    </div>

    <script src="{$PATH}/js/jquery-1.10.2.js"></script>
    <script src="{$PATH}/js/bootstrap.min.js"></script>
    <script src="{$PATH}/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    
    <!-- Page-Level Plugin Scripts - Morris -->
    <script src="{$PATH}/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="{$PATH}/js/plugins/morris/morris.js"></script>
    
    <script src="{$PATH}/js/mpos.js"></script>

