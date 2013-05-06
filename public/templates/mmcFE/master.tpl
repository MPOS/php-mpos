<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>ThePool</title>
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="{$PATH}/css/mainstyle.css" type="text/css" />
    <script type="text/javascript" src="{$PATH}/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.tools.min.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.img.preload.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.filestyle.mini.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.date_input.pack.js"></script>
    <script type="text/javascript" src="{$PATH}/js/facebox.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.visualize.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.visualize.tooltip.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.select_skin.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="{$PATH}/js/ajaxupload.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.pngfix.js"></script>
    <script type="text/javascript" src="{$PATH}/js/custom.js"></script>
    <script type="text/javascript" src="{$PATH}/js/tools.js"></script>
    <!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
    <style type="text/css" media="all">
      @import url("{$PATH}/css/style.css");
      @import url("{$PATH}/css/jquery.wysiwyg.css");
      @import url("{$PATH}/css/facebox.css");
      @import url("{$PATH}/css/visualize.css");
      @import url("{$PATH}/css/date_input.css");
    </style>
    <!--[if lt IE 8]><style type="text/css" media="all">@import url("css/ie.css");</style><![endif]-->
  </head>

<body>
<div id="hld">
  <div class="wrapper">
    <div id="siteheader">
      {include file="global/header.tpl"}
    </div>
    <br/>
    {include file="global/motd.tpl"}
    <br/>
    <div id="header">
      {include file="global/navigation.tpl"}
    </div>

    <div class="block withsidebar">
      <div class="block_head">
        <div class="bheadl"></div>
        <div class="bheadr"></div>
        {include file="global/userinfo.tpl"}
      </div>
      <div class="block_content">
        <div class="sidebar">
          {if $smarty.session.AUTHENTICATED}
          {include file="global/sidebar.tpl"}
          {else}
          {include file="global/login.tpl"}
          {/if}
        </div>
        <div class="sidebar_content" id="sb1">
          {if is_array($smarty.session.POPUP)}
            {section popup $smarty.session.POPUP}
              <div class="message {$smarty.session.POPUP[popup].TYPE|default:"success"}"><p>{$smarty.session.POPUP[popup].CONTENT}</p></div>
            {/section}
          {/if}
          {include file="$PAGE/$ACTION/$CONTENT"}
        </div>
        <div class"clear"></div>
        <div id="footer" style="font-size: 10px;">
          {include file="global/footer.tpl"}
        </div>
      </div>
    </div>
  </div>
  <div id="debug">
    {include file="system/debugger.tpl"}
  </div>
</div>
</body>
</html>
