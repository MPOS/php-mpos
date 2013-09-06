<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>{$GLOBAL.website.title}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="{$PATH}/css/mainstyle.css" type="text/css" />
    <script src="{$PATH}/js/jquery-1.9.1.min.js"></script>
    <script src="{$PATH}/js/jquery.browser.js"></script>
    <script src="{$PATH}/js/jquery.tablesorter.min.js"></script>
    <script src="{$PATH}/js/jquery.tablesorter.pager.js"></script>
    <script src="{$PATH}/js/jquery.visualize.js"></script>
    <script src="{$PATH}/js/jquery.tooltip.visualize.js"></script>
    <script src="{$PATH}/js/custom.js"></script>
    <script src="{$PATH}/js/tools.js"></script>
    <!--[if IE]><script type="text/javascript" src="{$PATH}/js/excanvas.js"></script><![endif]-->
    <style type="text/css" media="all">
      @import url("{$PATH}/css/style.css");
      @import url("{$PATH}/css/jquery.wysiwyg.css");
      @import url("{$PATH}/css/facebox.css");
      @import url("{$PATH}/css/visualize.css");
      @import url("{$PATH}/css/date_input.css");
    </style>
    <!--[if lt IE 8]><style type="text/css" media="all">@import url("{$PATH}/css/ie.css");</style><![endif]-->
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
          {if $smarty.session.AUTHENTICATED|default}
            {assign var=payout_system value=$GLOBAL.config.payout_system}
            {include file="global/sidebar_$payout_system.tpl"}
          {else}
          {include file="global/login.tpl"}
          {/if}
        </div>
        <div class="sidebar_content" id="sb1" style="margin-left: 13px">
          {if is_array($smarty.session.POPUP|default)}
            {section popup $smarty.session.POPUP}
              <div class="message {$smarty.session.POPUP[popup].TYPE|default:"success"}"><p>{$smarty.session.POPUP[popup].CONTENT}</p></div>
            {/section}
          {/if}
          {if file_exists($smarty.current_dir|cat:"/$PAGE/$ACTION/$CONTENT")}{include file="$PAGE/$ACTION/$CONTENT"}{else}Missing template for this page{/if}
        </div>
        <div class"clear"></div>
        <div id="footer" style="font-size: 10px;">
          {include file="global/footer.tpl"}
        </div>
      </div>
    </div>
  </div>
  <div id="debug">
    {nocache}{include file="system/debugger.tpl"}{/nocache}
  </div>
</div>
</body>
</html>
