<nav id="sidebar" class="sidebar nav-collapse collapse">
    <ul id="side-nav" class="side-nav">
        <li class="active">
            <a href="{$smarty.server.PHP_SELF}"><i class="fa fa-home"></i> <span class="name">Home</span></a>
        </li>

        <li>
            <a href="{$smarty.server.PHP_SELF}?page=irc"><i class="fa fa-comments"></i> <span class="name">IRC Chat</span></a>
        </li>

        <li>
            <a href="https://forums.chunkypools.com"><i class="fa fa-comments"></i> <span class="name">Forum</span></a>
        </li>

        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#forms-collapse"><i class="fa fa-tint"></i> <span class="name">Pools</span></a>
            <ul id="forms-collapse" class="panel-collapse collapse">
                <li><a href="https://chunkypools.com/doge/">Dogecoin (DOGE)</a></li>
                <li><a href="https://chunkypools.com/rpc/">RonPaulCoin (RPC)</a></li>
                <li><a href="https://chunkypools.com/dgb/">Digibyte (DGB)</a></li>
                <li><a href="https://chunkypools.com/kdc/">Klondikecoin (KDC)</a></li>
                <li><a href="https://chunkypools.com/pot/">Potcoin (POT)</a></li>
            </ul>
        </li>

        <li>&nbsp;</li>

        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        <li>
            <a href="{$smarty.server.PHP_SELF}?page=dashboard"><i class="fa fa-dashboard"></i> <span class="name">Dashboard</span></a>
        </li>
        {/if}

        {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#admin-collapse"><i class="fa fa-user-md"></i> <span class="name">Admin</span></a>
            <ul id="admin-collapse" class="panel-collapse collapse">
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
        </li>
        {/if}
        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse"
               data-parent="#side-nav" href="#account-collapse"><i class="fa fa-user-md"></i> <span class="name">My Account</span></a>
            <ul id="account-collapse" class="panel-collapse collapse">
              <li class="icon-user"><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Edit Account</a></li>
              <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">My Workers</a></li>
              <li class="icon-indent-left"><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transactions</a></li>
            {if !$GLOBAL.config.disable_notifications}<li class="icon-megaphone"><a href="{$smarty.server.PHP_SELF}?page=account&action=notifications">Notifications</a></li>{/if}
            </ul>
        </li>
        {/if}
        <li class="panel">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#side-nav" href="#stats-collapse"><i class="fa fa-bar-chart-o"></i> <span class="name">Statistics</span></a>
            <ul id="stats-collapse" class="panel-collapse collapse">
              <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
              <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Blocks</a></li>
              <li class="icon-record"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round">Round</a></li>
            </ul>
        </li>

        <li>&nbsp;</li>

        {if !$GLOBAL.website.about.disabled}
        <li>
            <a href="{$smarty.server.PHP_SELF}?page=about&action=pool"><i class="fa fa-question"></i> <span class="name">FAQ / Help</span></a>
        </li>
        {/if}

        <li>
            <a href="{$smarty.server.PHP_SELF}?page=gettingstarted"><i class="fa fa-info"></i> <span class="name">Getting Started</span></a>
        </li>

        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        {if !$GLOBAL.config.disable_contactform|default:"0" == 1}
        <li class="panel"><a href="{$smarty.server.PHP_SELF}?page=contactform"><i class="fa fa-inbox"></i> Support</a></li>
        {/if}
        <li class="panel"><a href="{$smarty.server.PHP_SELF}?page=logout"><i class="fa fa-sign-out"></i> Logout</a></li>
        {else}
        <li class="panel"><a href="{$smarty.server.PHP_SELF}?page=login"><i class="fa fa-sign-out"></i> Login</a></li>
        <li class="panel"><a href="{$smarty.server.PHP_SELF}?page=register"><i class="fa fa-pencil"></i> Sign Up</a></li>
        <li class="panel"><a href="{$smarty.server.PHP_SELF}?page=support"><i class="fa fa-inbox"></i> Support</a></li>
        {/if}
    </ul>

    {if $SITECOINNAME}
    <div id="coin-logo">
      <img src="{$PATH}/img/{$SITECOINNAME|lower}120.png" />
    </div>
    {/if}
</nav>
