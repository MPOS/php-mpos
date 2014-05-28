    <p><strong>MPOS</strong> by TheSerapher, available on <a href="https://github.com/MPOS/php-mpos">GitHub</a></p>
    <p>Please <strong>Donate</strong> to support contests and giveaways here on AwesomeHash: DOGE DTSSpy6S1Xut6VH2mJ9VH5TDQqmHfDhUPY</p>
    <p>Please <strong>Donate</strong> to TheSerapher LTC: Lge95QR2frp9y1wJufjUPCycVsg5gLJPW8</p>
    <p><strong>Copyright &copy; 2013 Sebastian Grewe</strong>, Theme by <a href="http://www.medialoot.com">MediaLoot</a></p>
    {literal}
      <!-- Piwik -->
        <script type="text/javascript">
          var _paq = _paq || [];
          _paq.push(["trackPageView"]);
          _paq.push(["enableLinkTracking"]);
        
          (function() {
            var u=(("https:" == document.location.protocol) ? "https" : "http") + "://awesomehash.com/stats/";
            _paq.push(["setTrackerUrl", u+"piwik.php"]);
            _paq.push(["setSiteId", "2"]);
            var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
            g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <!-- End Piwik Code -->
    {/literal}
    {if $DEBUG > 0}
    <div id="debug">
      {nocache}{include file="system/debugger.tpl"}{/nocache}
    </div>
    {/if}
