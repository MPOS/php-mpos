<!DOCTYPE html>
<html>
<head>
    <title>{$GLOBAL.website.title} | {$smarty.request.page|escape|default:"home"|capitalize}</title>
    <link href="{$PATH}/css/layout.css" rel="stylesheet">
    <link href="{$PATH}/css/style.css" rel="stylesheet">
    <link href="{$PATH}/css/fontello.css" rel="stylesheet">
    <link href="{$PATH}/css/bootstrap-switch.css" rel="stylesheet">
    <link rel="shortcut icon" href="{$PATH}/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{$PATH}/img/favicon.ico" type="image/x-icon">	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta charset="utf-8">

    <!-- jquery and friends -->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$PATH}/js/hideshow.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.visualize.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.tablesorter.pager.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$PATH}/js/jquery.equalHeight.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script type="text/javascript" src="{$PATH}/js/justgage.1.0.1.min.js"></script>
    <!-- <script type="text/javascript" src="{$PATH}/js/custom.js"></script> -->

      <!-- jquery plugins -->
    <!-- <script src="{$PATH}/lib/icheck.js/jquery.icheck.js"></script> -->
    <script src="{$PATH}/lib/bootstrap-switch.js"></script>
    <script src="{$PATH}/lib/jquery-plugins/jquery.sparkline.js"></script>
    <script src="{$PATH}/lib/jquery-ui-1.10.3.custom.js"></script>
    <script src="{$PATH}/lib/jquery-plugins/jquery.slimscroll.js"></script>


    <!-- d3, nvd3-->
    <!-- <script src="{$PATH}/lib/nvd3/lib/d3.v2.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/nv.d3.custom.js"></script> -->

    <!-- nvd3 models -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/scatter.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/axis.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/legend.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/multiBar.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/multiBarChart.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/line.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/src/models/lineChart.js"></script> -->
    <!-- <script src="{$PATH}/lib/nvd3/stream_layers.js"></script> -->

    <!-- rickshaw -->
    <!-- <script src="{$PATH}/lib/rickshaw/rickshaw.js"></script> -->
    <!-- <script src="{$PATH}/lib/rickshaw/rickshaw&#45;extensions.js"></script> -->

    <!--backbone and friends -->
    <!-- <script src="{$PATH}/lib/backbone/underscore&#45;min.js"></script> -->
    <!-- <script src="{$PATH}/lib/backbone/backbone&#45;min.js"></script> -->
    <!-- <script src="{$PATH}/lib/backbone/backbone.localStorage&#45;min.js"></script> -->

    <!-- bootstrap default plugins -->
    <script src="{$PATH}/lib/bootstrap/transition.js"></script>
    <script src="{$PATH}/lib/bootstrap/collapse.js"></script>
    <script src="{$PATH}/lib/bootstrap/alert.js"></script>
    <script src="{$PATH}/lib/bootstrap/tooltip.js"></script>
    <script src="{$PATH}/lib/bootstrap/popover.js"></script>
    <script src="{$PATH}/lib/bootstrap/button.js"></script>
    <script src="{$PATH}/lib/bootstrap/tab.js"> </script>
    <script src="{$PATH}/lib/bootstrap/dropdown.js"></script>

    <!-- basic application js-->
    <script src="{$PATH}/js/app.js"></script>
    <script src="{$PATH}/js/forms.js"></script>
    <script src="{$PATH}/js/forms-elements.js"></script>
    <script src="{$PATH}/js/ui-buttons.js"></script>
    <script src="{$PATH}/js/ui-dialogs.js"></script>
    <!-- <script src="{$PATH}/js/settings.js"></script> -->

    <!-- page specific -->
    <!-- <script src="{$PATH}/js/index.js"></script> -->
    <!-- <script src="{$PATH}/js/realtime.js"></script> -->
    <!-- <script src="{$PATH}/js/chat.js"></script> -->

    {literal}<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-48865762-1', 'chunkypools.com');
    ga('send', 'pageview');

    </script>{/literal}
</head>

<body class="background">
  <div class="logo">
    <h4><a href="/"><img src="{$PATH}/images/logo120.png" width="60"> Chunky <strong>Pools</strong></a></h4>
    <!-- <h4><a href="/"><img src="https://i.imgur.com/8sAPFgb.png" width="60"> Chunky <strong>Pools</strong></a></h4> -->
  </div>

  {include file="global/navigation.tpl"}

  <div class="wrap">
    {include file="global/header.tpl"}

    <div class="content container">
      {include file="global/popup.tpl"}

      {if $CONTENT != "empty" or $CONTENT != ""}{if file_exists($smarty.current_dir|cat:"/$PAGE/$ACTION/$CONTENT")}{include file="$PAGE/$ACTION/$CONTENT"}{else}Missing template for this page{/if}{/if}
    </div>

  </div>
</body>
</html>
