    <hr/>
    <li class="icon-home"><a href="{$smarty.server.SCRIPT_NAME}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>My Account</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.SCRIPT_NAME}?page=dashboard">Dashboard</a></li>
      <li class="icon-user"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit">Edit Account</a></li>
      <li class="icon-photo"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers">My Workers</a></li>
      <li class="icon-indent-left"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=transactions">Transactions</a></li>
    {if !$GLOBAL.config.disable_notifications}<li class="icon-megaphone"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=notifications">Notifications</a></li>{/if}
    {if !$GLOBAL.config.disable_invitations}<li class="icon-plus"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=invitations">Invitations</a></li>{/if}
      <li class="icon-barcode"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=qrcode">QR Codes</a></li>
    </ul>
    </li>
    {/if}
    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
    <h3>Admin Panel</h3>
    <ul class="toggle">
      <li class="icon-gauge"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard">Dashboard</a></li>
      <li class="icon-bell"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">Monitoring</a></li>
      <li class="icon-torso"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user">User Info</a></li>
      <li class="icon-money"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet">Wallet Info</a></li>
      <li class="icon-exchange"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions">Transactions</a></li>
      <li class="icon-cog"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings">Settings</a></li>
      <li class="icon-doc"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news">News</a></li>
      <li class="icon-chart"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=reports">Reports</a></li>
      <li class="icon-edit"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations">Registrations</a></li>
      <li class="icon-users"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations">Invitations</a></li>
      <li class="icon-photo"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=poolworkers">Pool Workers</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=templates">Templates</a></li>
    </ul>
    {/if}
    <h3>Statistics</h3>
    <ul class="toggle">
     {acl_check icon='icon-align-left' page='statistics' action='pool' name='Pool' acl=$GLOBAL.acl.pool.statistics fallback='page=statistics'}
     {acl_check icon='icon-th-large' page='statistics' action='blocks' name='Blocks' acl=$GLOBAL.acl.block.statistics}
     {acl_check icon='icon-chart' page='statistics' action='round' name='Round' acl=$GLOBAL.acl.round.statistics}
     {acl_check icon='icon-search' page='statistics' action='blockfinder' name='Blockfinder' acl=$GLOBAL.acl.blockfinder.statistics}
     {acl_check icon='icon-bell' page='statistics' action='uptime' name='Uptime' acl=$GLOBAL.acl.uptime.statistics}
     {acl_check icon='icon-chart' page='statistics' action='graphs' name='Graphs' acl=$GLOBAL.acl.graphs.statistics}
    </ul>
    <h3>Help</h3>
    <ul class="toggle">
      <li class="icon-desktop"><a href="{$smarty.server.SCRIPT_NAME}?page=gettingstarted">Getting Started</a></li>
      {acl_check icon='icon-doc' page='about' action='pool' name='About' acl=$GLOBAL.acl.about.page}
      {acl_check icon='icon-money' page='about' action='donors' name='Donors' acl=$GLOBAL.acl.donors.page}
      {acl_check icon='icon-megaphone' page='about' action='chat' name='Web Chat' acl=$GLOBAL.acl.chat.page}
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      <li class="icon-off"><a href="{$smarty.server.SCRIPT_NAME}?page=logout">Logout</a></li>
      {else}
      <li class="icon-login"><a href="{$smarty.server.SCRIPT_NAME}?page=login">Login</a></li>
      <li class="icon-pencil"><a href="{$smarty.server.SCRIPT_NAME}?page=register">Sign Up</a></li>
      {/if}
      {acl_check icon='icon-mail' page='contactform' action='' name='Contact' acl=$GLOBAL.acl.contactform}
      <li class="icon-doc"><a href="{$smarty.server.SCRIPT_NAME}?page=tac">Terms and Conditions</a></li>
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
    {if !$GLOBAL.website.api.disabled && !$GLOBAL.config.disable_navbar && !$GLOBAL.config.disable_navbar_api}
      {include file="global/navjs_api.tpl"}
    {else}
      {include file="global/navjs_static.tpl"}
    {/if}
    {/if}
