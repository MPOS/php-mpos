    <hr/>
    <li class="icon-home"><a href="{$smarty.server.PHP_SELF}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>Mein Benutzer</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.PHP_SELF}?page=dashboard">Dashboard</a></li>
      <li class="icon-user"><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Daten editieren</a></li>
      <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">Meine Arbeiter</a></li>
      <li class="icon-indent-left"><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transaktionen</a></li>
    {if !$GLOBAL.config.disable_notifications}<li class="icon-megaphone"><a href="{$smarty.server.PHP_SELF}?page=account&action=notifications">Nachrichten</a></li>{/if}
    {if !$GLOBAL.config.disable_invitations}<li class="icon-plus"><a href="{$smarty.server.PHP_SELF}?page=account&action=invitations">Einladungen</a></li>{/if}
      <li class="icon-barcode"><a href="{$smarty.server.PHP_SELF}?page=account&action=qrcode">QR Code</a></li>
    </ul>
    </li>
    {/if}
    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
    <h3>Admin Panel</h3>
    <ul class="toggle">
      <li class="icon-bell"><a href="{$smarty.server.PHP_SELF}?page=admin&action=monitoring">Monitoring</a></li>
      <li class="icon-torso"><a href="{$smarty.server.PHP_SELF}?page=admin&action=user">Meine Daten</a></li>
      <li class="icon-money"><a href="{$smarty.server.PHP_SELF}?page=admin&action=wallet">Brieftasche</a></li>
      <li class="icon-exchange"><a href="{$smarty.server.PHP_SELF}?page=admin&action=transactions">Transaktionen</a></li>
      <li class="icon-cog"><a href="{$smarty.server.PHP_SELF}?page=admin&action=settings">Einstellungen</a></li>
      <li class="icon-doc"><a href="{$smarty.server.PHP_SELF}?page=admin&action=news">News</a></li>
      <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=admin&action=reports">Reporte</a></li>
      <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=admin&action=poolworkers">Pool Arbeiter</a></li>
    </ul>
    {/if}
    {if $smarty.session.AUTHENTICATED|default}
    <h3>Statistics</h3>
    <ul class="toggle">
      <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
      <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Bl&ouml;cke</a></li>
      <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=graphs">Graphiken</a></li>
      <li class="icon-record"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round">Runde</a></li>
    </ul>
    {else}
    <h3>Statistics</h3>
    <ul class="toggle">
     {if $GLOBAL.acl.pool.statistics}
     <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
     {else}
     <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics">Statistiken</a>
     {/if}
     {if $GLOBAL.acl.block.statistics}
     <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Bl&ouml;cke</a></li>
     {/if}
     {if $GLOBAL.acl.round.statistics}
     <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round">Runde</a></li>
    {/if}
    </ul>
    {/if}
    <h3>Help</h3>
    <ul class="toggle">
      <li class="icon-desktop"><a href="{$smarty.server.PHP_SELF}?page=gettingstarted">Starthilfe</a></li>
      <li class="icon-doc"><a href="{$smarty.server.PHP_SELF}?page=about&action=pool">&Uuml;ber</a></li>
      <li class="icon-money"><a href="{$smarty.server.PHP_SELF}?page=about&action=donors">Spender</a></li>
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      {if !$GLOBAL.config.disable_contactform|default:"0" == 1}
      <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=contactform">Support</a></li>
      {/if}
      <li class="icon-off"><a href="{$smarty.server.PHP_SELF}?page=logout">Abmelden</a></li>
      {else}
      <li class="icon-login"><a href="{$smarty.server.PHP_SELF}?page=login">Anmelden</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.PHP_SELF}?page=register">Registrierung</a></li>
      <li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=support">Support</a></li>
      {/if}
    </ul>
    <ul>
      <hr/>
    </ul>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
     <br />
    {else}
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
