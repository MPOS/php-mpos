<div class="overlaymenu">
  {if $smarty.session.AUTHENTICATED|default:"0" == 1 || $GLOBAL.acl.menu.loggedin|default:"0" == 0}
    <div id="cssmenu" class="main-navigation">
      <ul>
        <li {if $smarty.get.page|default:"0" eq "home" || $smarty.post.page|default:"0" eq "home"}class="active"{/if}>
          <a href="{$smarty.server.SCRIPT_NAME}?page=home"><i class="fa fa-home"></i>Home</a>
        </li>
        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        <li {if $smarty.get.page|default:"0" eq "dashboard" || $smarty.post.page|default:"0" eq "dashboard"}class="active"{/if}>
          <a href="{$smarty.server.SCRIPT_NAME}?page=dashboard"><i class="fa fa-dashboard"></i>Dashboard</a>
        </li>
        {/if}
        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
          <li {if $smarty.get.page|default:"0" eq "account" || $smarty.post.page|default:"0" eq "account"}class="active"{/if}>
            <a href="#"><i class="fa fa-user-md"></i>My Account</a>
            <ul>
              <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit"><i class="fa fa-edit fa-fw"></i> Edit Account</a></li>
              <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers"><i class="fa fa-desktop fa-fw"></i> My Workers</a></li>
              <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=transactions"><i class="fa fa-credit-card fa-fw"></i> Transactions</a></li>
              {if !$GLOBAL.config.disable_transactionsummary}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=earnings"><i class="fa fa-money fa-fw"></i> Earnings</a></li>{/if}
              {if !$GLOBAL.config.disable_notifications}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=notifications"><i class="fa fa-bullhorn fa-fw"></i> Notifications</a></li>{/if}
              {if !$GLOBAL.config.disable_invitations}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>{/if}
              {if !$GLOBAL.acl.qrcode}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=qrcode"><i class="fa fa-qrcode fa-fw"></i> QR Codes</a></li>{/if}
            </ul>
          </li>
        {/if}
        {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
          <li {if $smarty.get.page|default:"0" eq "admin" || $smarty.post.page|default:"0" eq "admin"}class="active"{/if}>
            <a href="#"><i class="fa fa-wrench"></i>Admin Panel</a>
            <ul>
              <li class="has-sub"><span class="submenu-button"></span><a title="" data-original-title="" href="#"><i class="fa fa-gears fa-fw"></i> System</a>
                <ul>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=setup"><i class="fa fa-book fa-fw"></i> Setup</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring"><i class="fa fa-bell-o fa-fw"></i> Monitoring</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings"><i class="fa fa-gears fa-fw"></i> Settings</a></li>
                </ul>
              </li>
              <li class="has-sub"><span class="submenu-button"></span><a title="" data-original-title="" href="#"><i class="fa fa-bank fa-fw"></i> Funds</a>
                <ul>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet"><i class="fa fa-money fa-fw"></i> Wallet Info</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions"><i class="fa fa-tasks fa-fw"></i> Transactions</a></li>
                </ul>
              </li>
              <li class="has-sub"><span class="submenu-button"></span><a title="" data-original-title="" href="#"><i class="fa fa-newspaper-o fa-fw"></i> News</a>
                <ul>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news"><i class="fa fa-list-alt fa-fw"></i> Site News</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=newsletter"><i class="fa fa-list-alt fa-fw"></i> Newsletter</a></li>
                </ul>
              </li>
              <li class="has-sub"><span class="submenu-button"></span><a title="" data-original-title="" href="#"><i class="fa fa-users fa-fw"></i> Users</a>
                <ul>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user"><i class="fa fa-user fa-fw"></i> User Info</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=reports"><i class="fa fa-list-ol fa-fw"></i> Reports</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations"><i class="fa fa-pencil-square-o fa-fw"></i> Registrations</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>
                  <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=poolworkers"><i class="fa fa-desktop fa-fw"></i> Pool Workers</a></li>
                </ul>
              </li>
            </ul>
          </li>
        {/if}
        {if ($GLOBAL.acl.statistics.loggedin|default:"0" == 0 && ($smarty.session.AUTHENTICATED|default:"0" == 0 OR $smarty.session.AUTHENTICATED|default:"0" == 1)) OR ($GLOBAL.acl.statistics.loggedin|default:"0" == 1 && $smarty.session.AUTHENTICATED|default:"0" == 1)}
          <li {if $smarty.get.page|default:"0" eq "statistics" || $smarty.post.page|default:"0" eq "statistics"}class="active"{/if}>
            <a href="#"><i class="fa fa-bar-chart-o"></i>Statistics</a>
            <ul>
              {acl_check page='statistics' action='pool' name='<i class="fa fa-align-left fa-fw"></i> Pool' acl=$GLOBAL.acl.pool.statistics fallback='page=statistics'}
              {acl_check page='statistics' action='blocks' name='<i class="fa fa-th-large fa-fw"></i> Blocks' acl=$GLOBAL.acl.block.statistics}
              {acl_check page='statistics' action='round' name='<i class="fa fa-refresh fa-fw"></i> Round' acl=$GLOBAL.acl.round.statistics}
              {acl_check page='statistics' action='blockfinder' name='<i class="fa fa-search fa-fw"></i> Blockfinder' acl=$GLOBAL.acl.blockfinder.statistics}
              {acl_check page='statistics' action='uptime' name='<i class="fa fa-clock-o fa-fw"></i> Uptime' acl=$GLOBAL.acl.uptime.statistics}
              {acl_check page='statistics' action='graphs' name='<i class="fa fa-signal fa-fw"></i> Graphs' acl=$GLOBAL.acl.graphs.statistics}
              {acl_check page='statistics' action='donors' name='<i class="fa fa-bitbucket fa-fw"></i> Donors' acl=$GLOBAL.acl.donors.page}
            </ul>
          </li>
        {/if}
        {if ($GLOBAL.acl.help.loggedin|default:"0" == 0 && ($smarty.session.AUTHENTICATED|default:"0" == 0 OR $smarty.session.AUTHENTICATED|default:"0" == 1)) OR ($GLOBAL.acl.help.loggedin|default:"0" == 1 && $smarty.session.AUTHENTICATED|default:"0" == 1)}
          <li {if $smarty.get.page|default:"0" eq "gettingstarted" || $smarty.get.page|default:"0" eq "about" || $smarty.post.page|default:"0" eq "gettingstarted" || $smarty.post.page|default:"0" eq "about"}class="active"{/if}>
            <a href="#"><i class="fa fa-question"></i>Help</a>
            <ul>
              <li><a href="{$smarty.server.SCRIPT_NAME}?page=gettingstarted"><i class="fa fa-question fa-fw"></i> Getting Started</a></li>
              {acl_check page='about' action='pool' name='<i class="fa fa-info fa-fw"></i> About' acl=$GLOBAL.acl.about.page}
              {acl_check page='about' action='chat' name='<i class="fa fa-comments-o fa-fw"></i> Web Chat' acl=$GLOBAL.acl.chat.page}
              {acl_check page='about' action='moot' name='<i class="fa fa-ticket fa-fw"></i> Forum' acl=$GLOBAL.acl.moot.forum}
            </ul>
          </li>
        {/if}
        <li {if $smarty.get.page|default:"0" eq "register" || $smarty.get.page|default:"0" eq "login" || $smarty.get.page|default:"0" eq "logout" || $smarty.get.page|default:"0" eq "tac" || $smarty.get.page|default:"0" eq "contactform" || $smarty.post.page|default:"0" eq "register" || $smarty.post.page|default:"0" eq "login" || $smarty.post.page|default:"0" eq "logout" || $smarty.post.page|default:"0" eq "tac" || $smarty.post.page|default:"0" eq "contactform"}class="active"{/if}>
          <a href="#"><i class="fa fa-tasks"></i>Other</a>
          <ul>
            {if $smarty.session.AUTHENTICATED|default:"0" == 1}
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            {else}
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=login"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=register"><i class="fa fa-pencil fa-fw"></i> Sign Up</a></li>
            {/if}
            {acl_check page='contactform' action='' name='<i class="fa fa-envelope fa-fw"></i> Contact' acl=$GLOBAL.acl.contactform}
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=tac"><i class="fa fa-book fa-fw"></i> Terms and Conditions</a></li>
          </ul>
        </li>
      </ul>
    </div>
    {if $smarty.get.page|default:"0" eq "home" || $smarty.post.page|default:"0" eq "home"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Home</a></li>
      </ul>
    </div>
    {/if}
    {if $smarty.get.page|default:"0" eq "dashboard" || $smarty.post.page|default:"0" eq "dashboard"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Dashboard</a></li>
      </ul>
    </div>
    {/if}
    {if $smarty.get.page|default:"0" eq "account" || $smarty.post.page|default:"0" eq "account"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">My Account</a></li>
        {if $GLOBAL.userdata.is_admin == 1}
        <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit"><i class="fa fa-edit fa-fw"></i> Edit Account</a></li>
        <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers"><i class="fa fa-desktop fa-fw"></i> My Workers</a></li>
        <li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=transactions"><i class="fa fa-credit-card fa-fw"></i> Transactions</a></li>
        {if !$GLOBAL.config.disable_transactionsummary}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=earnings"><i class="fa fa-money fa-fw"></i> Earnings</a></li>{/if}
        {if !$GLOBAL.config.disable_notifications}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=notifications"><i class="fa fa-bullhorn fa-fw"></i> Notifications</a></li>{/if}
        {if !$GLOBAL.config.disable_invitations}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>{/if}
        {if !$GLOBAL.acl.qrcode}<li class="hidden-sm hidden-xs"><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=qrcode"><i class="fa fa-qrcode fa-fw"></i> QR Codes</a></li>{/if}
        {/if}
      </ul>
    </div>
    {/if}
    {if $smarty.get.page|default:"0" eq "admin" || $smarty.post.page|default:"0" eq "admin"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        {if $smarty.get.action|default:"0" eq "setup" || $smarty.get.action|default:"0" eq "dashboard" || $smarty.get.action|default:"0" eq "monitoring" || $smarty.get.action|default:"0" eq "settings" || $smarty.post.action|default:"0" eq "setup" || $smarty.post.action|default:"0" eq "dashboard" || $smarty.post.action|default:"0" eq "monitoring" || $smarty.post.action|default:"0" eq "settings"}
        <li><a class="heading">System</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=setup"><i class="fa fa-book fa-fw"></i> Setup</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring"><i class="fa fa-bell-o fa-fw"></i> Monitoring</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings"><i class="fa fa-gears fa-fw"></i> Settings</a></li>
        {/if}
        {if $smarty.get.action|default:"0" eq "wallet" || $smarty.get.action|default:"0" eq "transactions" || $smarty.post.action|default:"0" eq "wallet" || $smarty.post.action|default:"0" eq "transactions"}
        <li><a class="heading">Funds</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet"><i class="fa fa-money fa-fw"></i> Wallet Info</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions"><i class="fa fa-tasks fa-fw"></i> Transactions</a></li>
        {/if}
        {if $smarty.get.action|default:"0" eq "news" || $smarty.get.action|default:"0" eq "news_edit" || $smarty.get.action|default:"0" eq "newsletter" || $smarty.post.action|default:"0" eq "news" || $smarty.post.action|default:"0" eq "news_edit" || $smarty.post.action|default:"0" eq "newsletter"}
        <li><a class="heading">News</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news"><i class="fa fa-list-alt fa-fw"></i> Site News</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=newsletter"><i class="fa fa-list-alt fa-fw"></i> Newsletter</a></li>
        {/if}
        {if $smarty.get.action|default:"0" eq "userdetails" || $smarty.get.action|default:"0" eq "user" || $smarty.get.action|default:"0" eq "reports" || $smarty.get.action|default:"0" eq "registrations" || $smarty.get.action|default:"0" eq "invitations" || $smarty.get.action|default:"0" eq "poolworkers" || $smarty.post.action|default:"0" eq "userdetails" || $smarty.post.action|default:"0" eq "user" || $smarty.post.action|default:"0" eq "reports" || $smarty.post.action|default:"0" eq "registrations" || $smarty.post.action|default:"0" eq "invitations" || $smarty.post.action|default:"0" eq "poolworkers"}
        <li><a class="heading">Users</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user"><i class="fa fa-user fa-fw"></i> User Info</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=reports"><i class="fa fa-list-ol fa-fw"></i> Reports</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations"><i class="fa fa-pencil-square-o fa-fw"></i> Registrations</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=poolworkers"><i class="fa fa-desktop fa-fw"></i> Pool Workers</a></li>
        {/if}
      </ul>
    </div>
    {/if}
    {if ($GLOBAL.acl.statistics.loggedin|default:"0" == 0 && ($smarty.session.AUTHENTICATED|default:"0" == 0 OR $smarty.session.AUTHENTICATED|default:"0" == 1)) OR ($GLOBAL.acl.statistics.loggedin|default:"0" == 1 && $smarty.session.AUTHENTICATED|default:"0" == 1)}
    {if $smarty.get.page|default:"0" eq "statistics" || $smarty.post.page|default:"0" eq "statistics"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Statistics</a></li>
        {acl_check page='statistics' action='pool' name='<i class="fa fa-align-left fa-fw"></i> Pool' acl=$GLOBAL.acl.pool.statistics fallback='page=statistics'}
        {acl_check page='statistics' action='blocks' name='<i class="fa fa-th-large fa-fw"></i> Blocks' acl=$GLOBAL.acl.block.statistics}
        {acl_check page='statistics' action='round' name='<i class="fa fa-refresh fa-fw"></i> Round' acl=$GLOBAL.acl.round.statistics}
        {acl_check page='statistics' action='blockfinder' name='<i class="fa fa-search fa-fw"></i> Blockfinder' acl=$GLOBAL.acl.blockfinder.statistics}
        {acl_check page='statistics' action='uptime' name='<i class="fa fa-clock-o fa-fw"></i> Uptime' acl=$GLOBAL.acl.uptime.statistics}
        {acl_check page='statistics' action='graphs' name='<i class="fa fa-signal fa-fw"></i> Graphs' acl=$GLOBAL.acl.graphs.statistics}
        {acl_check page='statistics' action='donors' name='<i class="fa fa-bitbucket fa-fw"></i> Donors' acl=$GLOBAL.acl.donors.page}
      </ul>
    </div>
    {/if}
    {/if}
    {if ($GLOBAL.acl.statistics.loggedin|default:"0" == 0 && ($smarty.session.AUTHENTICATED|default:"0" == 0 OR $smarty.session.AUTHENTICATED|default:"0" == 1)) OR ($GLOBAL.acl.statistics.loggedin|default:"0" == 1 && $smarty.session.AUTHENTICATED|default:"0" == 1)}
    {if $smarty.get.page|default:"0" eq "gettingstarted" || $smarty.get.page|default:"0" eq "about" || $smarty.post.page|default:"0" eq "gettingstarted" || $smarty.post.page|default:"0" eq "about"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Help</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=gettingstarted"><i class="fa fa-question fa-fw"></i> Getting Started</a></li>
        {acl_check page='about' action='pool' name='<i class="fa fa-info fa-fw"></i> About' acl=$GLOBAL.acl.about.page}
        {acl_check page='about' action='chat' name='<i class="fa fa-comments-o fa-fw"></i> Web Chat' acl=$GLOBAL.acl.chat.page}
        {acl_check page='about' action='moot' name='<i class="fa fa-ticket fa-fw"></i> Forum' acl=$GLOBAL.acl.moot.forum}
      </ul>
    </div>
    {/if}
    {/if}
    {if $smarty.get.page|default:"0" eq "register" || $smarty.get.page|default:"0" eq "login" || $smarty.get.page|default:"0" eq "logout" || $smarty.get.page|default:"0" eq "tac" || $smarty.get.page|default:"0" eq "contactform" || $smarty.post.page|default:"0" eq "register" || $smarty.post.page|default:"0" eq "login" || $smarty.post.page|default:"0" eq "logout" || $smarty.post.page|default:"0" eq "tac" || $smarty.post.page|default:"0" eq "contactform"}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Other</a></li>
        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
        {else}
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=login"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=register"><i class="fa fa-pencil fa-fw"></i> Sign Up</a></li>
        {/if}
        {acl_check page='contactform' action='' name='<i class="fa fa-envelope fa-fw"></i> Contact' acl=$GLOBAL.acl.contactform}
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=tac"><i class="fa fa-book fa-fw"></i> Terms and Conditions</a></li>
      </ul>
    </div>
    {/if}
  {else}
    <div class="sub-nav hidden-sm hidden-xs">
      <ul>
        <li><a class="heading">Welcome to {$GLOBAL.website.name}</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=login"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
        <li><a href="{$smarty.server.SCRIPT_NAME}?page=register"><i class="fa fa-pencil fa-fw"></i> Sign Up</a></li>
      </ul>
    </div>
  {/if}
</div>