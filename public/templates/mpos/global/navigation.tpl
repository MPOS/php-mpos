    <hr/>
    <li class="icon-home"><a href="{$smarty.server.PHP_SELF}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>My Account</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.PHP_SELF}?page=dashboard">Dashboard</a></li>
      <li class="icon-user"><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Edit Account</a></li>
      <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">My Workers</a></li>
      <li class="icon-indent-left"><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transactions</a></li>
    {if !$GLOBAL.config.disable_notifications}<li class="icon-megaphone"><a href="{$smarty.server.PHP_SELF}?page=account&action=notifications">Notifications</a></li>{/if}
    {if !$GLOBAL.config.disable_invitations}<li class="icon-plus"><a href="{$smarty.server.PHP_SELF}?page=account&action=invitations">Invitations</a></li>{/if}
      <li class="icon-barcode"><a href="{$smarty.server.PHP_SELF}?page=account&action=qrcode">QR Codes</a></li>
    </ul>
    </li>
    {/if}
    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
    <h3>Admin Panel</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.PHP_SELF}?page=admin&action=dashboard">Dashboard</a></li>
      <li class="icon-bell"><a href="{$smarty.server.PHP_SELF}?page=admin&action=monitoring">Monitoring</a></li>
      <li class="icon-torso"><a href="{$smarty.server.PHP_SELF}?page=admin&action=user">User Info</a></li>
      <li class="icon-money"><a href="{$smarty.server.PHP_SELF}?page=admin&action=wallet">Wallet Info</a></li>
      <li class="icon-exchange"><a href="{$smarty.server.PHP_SELF}?page=admin&action=transactions">Transactions</a></li>
      <li class="icon-cog"><a href="{$smarty.server.PHP_SELF}?page=admin&action=settings">Settings</a></li>
      <li class="icon-doc"><a href="{$smarty.server.PHP_SELF}?page=admin&action=news">News</a></li>
      <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=admin&action=reports">Reports</a></li>
      <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=admin&action=poolworkers">Pool Workers</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.PHP_SELF}?page=admin&action=templates">Templates</a></li>
    </ul>
    {/if}
    {if $smarty.session.AUTHENTICATED|default}
    <h3>Statistics</h3>
    <ul class="toggle">
      <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
      <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Blocks</a></li>
      <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=graphs">Graphs</a></li>
      <li class="icon-record"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round">Round</a></li>
      <li class="icon-search"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blockfinder">Finder</a></li>
      {if $GLOBAL.config.monitoring_uptimerobot_api_keys|default:"0"}<li class="icon-bell"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=uptime">Uptime</a></li>{/if}
    </ul>
    {else}
    <h3>Statistics</h3>
    <ul class="toggle">
     {if $GLOBAL.acl.pool.statistics}
     <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
     {else}
     <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics">Statistics</a>
     {/if}
     {if $GLOBAL.acl.block.statistics}
     <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Blocks</a></li>
     {/if}
     {if $GLOBAL.acl.round.statistics}
     <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round">Round</a></li>
     {/if}
     {if $GLOBAL.acl.blockfinder.statistics}
     <li class="icon-search"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blockfinder">Finder</a></li>
     {/if}
     {if $GLOBAL.acl.uptime.statistics}
     {if $GLOBAL.config.monitoring_uptimerobot_api_keys|default:"0"}<li class="icon-bell"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=uptime">Uptime</a></li>{/if}
     {/if}
    </ul>
    {/if}
    <h3>Help</h3>
    <ul class="toggle">
      <li class="icon-desktop"><a href="{$smarty.server.PHP_SELF}?page=gettingstarted">Getting Started</a></li>
      {if !$GLOBAL.website.about.disabled}
      <li class="icon-doc"><a href="{$smarty.server.PHP_SELF}?page=about&action=pool">About</a></li>
      {/if}
      {if !$GLOBAL.website.donors.disabled}
      <li class="icon-money"><a href="{$smarty.server.PHP_SELF}?page=about&action=donors">Donors</a></li>
      {/if}
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      {if $GLOBAL.config.disable_contactform|default:"0" != 1}
      <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=contactform">Support</a></li>
      {else}
      <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=support">Support</a></li>
      {/if}
      <li class="icon-off"><a href="{$smarty.server.PHP_SELF}?page=logout">Logout</a></li>
      {else}
      <li class="icon-login"><a href="{$smarty.server.PHP_SELF}?page=login">Login</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.PHP_SELF}?page=register">Sign Up</a></li>
      {if $GLOBAL.config.disable_contactform_guest|default:"0" == 1 || $GLOBAL.config.disable_contactform|default:"0" == 1}
        <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=support">Support</a></li>
      {else}
        <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=contactform">Support</a></li>
      {/if}
      <li class="icon-doc"><a href="{$smarty.server.PHP_SELF}?page=tac">Terms and Conditions</a></li>
      {/if}
    </ul>
    <ul>
      <hr/>
    </ul>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
     <br />
    {else}
    {if !$GLOBAL.website.api.disabled && !$GLOBAL.config.disable_navbar && !$GLOBAL.config.disable_navbar_api}
    <ul>
     <center>
      <div style="display: inline-block;">
      <i><u><b><font size="2">LIVE STATS</font></b></u></i>
      <div id="mr" style="width:180px; height:120px;"></div>
      <div id="hr" style="width:180px; height:120px;"></div>
      </div>
     </center>
    </ul>
      <hr/>
    {include file="global/navjs.tpl"}
    {/if}
    {/if}
