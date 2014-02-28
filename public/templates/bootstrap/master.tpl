<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{$GLOBAL.website.title} I {$smarty.request.page|escape|default:"home"|capitalize}</title>
	
	<!--<link rel="stylesheet" href="{$PATH}/css/layout.css" type="text/css" media="screen" />-->
	<!--<link rel="stylesheet" href="{$PATH}/css/fontello.css">-->
	<!--<link rel="stylesheet" href="{$PATH}/css/animation.css">-->
	<!--[if IE 7]><link rel="stylesheet" href="css/fontello-ie7.css"><![endif]-->
	<!--<link rel="stylesheet" href="{$PATH}/css/visualize.css" type="text/css" media="screen" />-->
	<!--<link rel="stylesheet" href="{$PATH}/css/custom.css" type="text/css" media="screen" />-->
	<link rel="stylesheet" href="{$PATH}/css/jquery.jqplot.min.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="{$PATH}/css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="{$PATH}/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="{$PATH}/js/hideshow.js" type="text/javascript"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.visualize.js"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.jqplot.min.js"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.equalHeight.js"></script>
	<script type="text/javascript" src="{$PATH}/js/raphael.2.1.2.min.js"></script>
	<script type="text/javascript" src="{$PATH}/js/justgage.1.0.1.min.js"></script>
	<script type="text/javascript" src="{$PATH}/js/custom.js"></script>
	<script type="text/javascript" src="{$PATH}/js/tinybox.js"></script>
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
    <!--[if lte IE 8]><script src="js/excanvas.min.js"></script><![endif]-->
    <script src="{$PATH}/js/plugins/flot/jquery.flot.js"></script>
    <script src="{$PATH}/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="{$PATH}/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="{$PATH}/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="{$PATH}/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="{$PATH}/js/mpos.js"></script>
  <script type="text/javascript" src="{$PATH}/js/justgage.1.0.1.min.js"></script>

</body>
</html>
