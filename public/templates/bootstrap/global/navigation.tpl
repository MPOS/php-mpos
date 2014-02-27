       <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="{$smarty.server.SCRIPT_NAME}"><i class="fa fa-home fa-fw"></i> Home</a>
                    </li>
                    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
                    <li>
                        <a href="{$smarty.server.SCRIPT_NAME}?page=dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    
                    <li {if $smarty.get.page eq "account"}class="active"{/if}>
                        <a href="#"><i class="fa fa-user-md fa-fw"></i> My Account<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit"><i class="fa fa-edit fa-fw"></i> Edit Account</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers"><i class="fa fa-desktop fa-fw"></i> My Workers</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=transactions"><i class="fa fa-credit-card fa-fw"></i> Transactions</a></li>
                          {if !$GLOBAL.config.disable_notifications}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=notifications"><i class="fa fa-bullhorn fa-fw"></i> Notifications</a></li>{/if}
                          {if !$GLOBAL.config.disable_invitations}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>{/if}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=qrcode"><i class="fa fa-qrcode fa-fw"></i> QR Codes</a></li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    {/if}
                    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
                    <li {if $smarty.get.page eq "admin"}class="active"{/if}>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Admin Panel<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring"><i class="fa fa-bell-o fa-fw"></i> Monitoring</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user"><i class="fa fa-user fa-fw"></i> User Info</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet"><i class="fa fa-money fa-fw"></i> Wallet Info</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions"><i class="fa fa-tasks fa-fw"></i> Transactions</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings"><i class="fa fa-gears fa-fw"></i> Settings</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news"><i class="fa fa-list-alt fa-fw"></i> News</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=reports"><i class="fa fa-list-ol fa-fw"></i> Reports</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations"><i class="fa fa-pencil-square-o fa-fw"></i> Registrations</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations"><i class="fa fa-users fa-fw"></i> Invitations</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=poolworkers"><i class="fa fa-desktop fa-fw"></i> Pool Workers</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=templates"><i class="fa fa-files-o fa-fw"></i> Templates</a></li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    {/if}
                    <li {if $smarty.get.page eq "statistics"}class="active"{/if}>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Statistics<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          {acl_check page='statistics' action='pool' name='<i class="fa fa-align-left fa-fw"></i> Pool' acl=$GLOBAL.acl.pool.statistics fallback='page=statistics'}
                          {acl_check page='statistics' action='blocks' name='<i class="fa fa-th-large fa-fw"></i> Blocks' acl=$GLOBAL.acl.block.statistics}
                          {acl_check page='statistics' action='round' name='<i class="fa fa-refresh fa-fw"></i> Round' acl=$GLOBAL.acl.round.statistics}
                          {acl_check page='statistics' action='blockfinder' name='<i class="fa fa-search fa-fw"></i> Blockfinder' acl=$GLOBAL.acl.blockfinder.statistics}
                          {acl_check page='statistics' action='uptime' name='<i class="fa fa-clock-o fa-fw"></i> Uptime' acl=$GLOBAL.acl.uptime.statistics}
                          {acl_check page='statistics' action='graphs' name='<i class="fa fa-signal fa-fw"></i> Graphs' acl=$GLOBAL.acl.graphs.statistics}
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li {if $smarty.get.page eq "gettingstarted" || $smarty.get.page eq "about"}class="active"{/if}>
                        <a href="#"><i class="fa fa-question fa-fw"></i> Help<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=gettingstarted"><i class="fa fa-question fa-fw"></i> Getting Started</a></li>
                          {acl_check page='about' action='pool' name='<i class="fa fa-info fa-fw"></i> About' acl=$GLOBAL.acl.about.page}
                          {acl_check page='about' action='donors' name='<i class="fa fa-bitbucket fa-fw"></i> Donors' acl=$GLOBAL.acl.donors.page}
                          {acl_check page='about' action='chat' name='<i class="fa fa-comments-o fa-fw"></i> Web Chat' acl=$GLOBAL.acl.chat.page}
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li {if $smarty.get.page eq "register" || $smarty.get.page eq "login" || $smarty.get.page eq "logout" || $smarty.get.page eq "tac" || $smarty.get.page eq "contactform"}class="active"{/if}>
                        <a href="#"><i class="fa fa-tasks fa-fw"></i> Other<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          {if $smarty.session.AUTHENTICATED|default:"0" == 1}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                          {else}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=login"><i class="fa fa-sign-in fa-fw"></i> Login</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=register"><i class="fa fa-sign-in fa-fw"></i> Sign Up</a></li>
                          {/if}
                          {acl_check page='contactform' action='' name='<i class="fa fa-envelope fa-fw"></i> Contact' acl=$GLOBAL.acl.contactform}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=tac"><i class="fa fa-book fa-fw"></i> Terms and Conditions</a></li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>
        <!-- /.navbar-static-side -->
