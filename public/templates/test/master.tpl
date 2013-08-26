<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>{$GLOBAL.website.name} I {$smarty.request.page|default:"home"|capitalize}</title>
	
	<link rel="stylesheet" href="{$PATH}/css/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$PATH}/css/visualize.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$PATH}/css/custom.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="{$PATH}/css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="{$PATH}/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="{$PATH}/js/hideshow.js" type="text/javascript"></script>
  <script type="text/javascript" src="{$PATH}/js/jquery.visualize.js"></script>
  <script type="text/javascript" src="{$PATH}/js/jquery.tooltip.visualize.js"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="{$PATH}/js/jquery.equalHeight.js"></script>
	<script type="text/javascript" src="{$PATH}/js/custom.js"></script>
  <!--[if IE]><script type="text/javascript" src="{$PATH}/js/excanvas.js"></script><![endif]-->
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
    {include file="global/footer.tpl"}
	</aside>
	<section id="main" class="column">
    {if is_array($smarty.session.POPUP|default)}
      {section popup $smarty.session.POPUP}
        <h4 class="{$smarty.session.POPUP[popup].TYPE|default:"info"}">{$smarty.session.POPUP[popup].CONTENT}</h4>
      {/section}
    {/if}
    {if file_exists($smarty.current_dir|cat:"/$PAGE/$ACTION/$CONTENT")}{include file="$PAGE/$ACTION/$CONTENT"}{else}Missing template for this page{/if}
		<div class="spacer"></div>
	</section>
</body>
</html>
