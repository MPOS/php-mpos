<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>{$GLOBAL.website.title} I {$smarty.request.page|escape|default:"home"|capitalize}</title>
	
	<link rel="stylesheet" href="{$PATH}/css/layout.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="{$PATH}/css/fontello.css">
  <link rel="stylesheet" href="{$PATH}/css/animation.css">
  <!--[if IE 7]><link rel="stylesheet" href="css/fontello-ie7.css"><![endif]-->
	<link rel="stylesheet" href="{$PATH}/css/visualize.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$PATH}/css/custom.css" type="text/css" media="screen" />
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

</head>
<body>
	<header id="header">
    {include file="global/header.tpl"}
	</header>
	<section id="secondary_bar">
    {include file="global/userinfo.tpl"}
    {include file="global/breadcrumbs.tpl"}
	</section>
	<aside id="sidebar" class="column">
    {include file="global/navigation.tpl"}
	</aside>
	<section id="main" class="column">
    {nocache}
    {if is_array($smarty.session.POPUP|default)}
      {section popup $smarty.session.POPUP}
        <h4 class="{$smarty.session.POPUP[popup].TYPE|default:"info"}">{$smarty.session.POPUP[popup].CONTENT nofilter}</h4>
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
		<div class="spacer"></div>
	</section>
  <footer class="footer">
    {include file="global/footer.tpl"}
  </footer>
</body>
</html>
