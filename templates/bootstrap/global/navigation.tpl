       <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="{$smarty.server.SCRIPT_NAME}"><i class="fa fa-home fa-fw"></i> {t}Home{/t}</a>
                    </li>
                    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
                    <li>
                        <a href="{$smarty.server.SCRIPT_NAME}?page=dashboard"><i class="fa fa-dashboard fa-fw"></i> {t}Dashboard{/t}</a>
                    </li>

                    <li {if $smarty.get.page|default:"0" eq "account"}class="active"{/if}>
                        <a href="#"><i class="fa fa-user-md fa-fw"></i> {t}My Account{/t}<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=edit"><i class="fa fa-edit fa-fw"></i> {t}Edit Account{/t}</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers"><i class="fa fa-desktop fa-fw"></i> {t}My Workers{/t}</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=transactions"><i class="fa fa-credit-card fa-fw"></i> {t}Transactions{/t}</a></li>
                          {if !$GLOBAL.config.disable_transactionsummary}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=earnings"><i class="fa fa-money fa-fw"></i> {t}Earnings{/t}</a></li>{/if}
                          {if !$GLOBAL.config.disable_notifications}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=notifications"><i class="fa fa-bullhorn fa-fw"></i> {t}Notifications{/t}</a></li>{/if}
                          {if !$GLOBAL.config.disable_invitations}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=invitations"><i class="fa fa-users fa-fw"></i> {t}Invitations{/t}</a></li>{/if}
                          {if !$GLOBAL.acl.qrcode}<li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=qrcode"><i class="fa fa-qrcode fa-fw"></i> {t}QR Codes{/t}</a></li>{/if}
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    {/if}
                    {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.is_admin == 1}
                    <li {if $smarty.get.page|default:"0" eq "admin"}class="active"{/if}>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> {t}Admin Panel{/t}<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li {if $smarty.get.action|default:"0" eq "dashboard" || $smarty.get.action|default:"0" eq "monitoring" || $smarty.get.action|default:"0" eq "settings"}class="active"{/if}>
                            <a href="#"><i class="fa fa-linux fa-fw"></i> {t}System{/t} <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=setup"><i class="fa fa-book fa-fw"></i> {t}Setup{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=dashboard"><i class="fa fa-dashboard fa-fw"></i> {t}Dashboard{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring"><i class="fa fa-bell-o fa-fw"></i> {t}Monitoring{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=settings"><i class="fa fa-gears fa-fw"></i> {t}Settings{/t}</a></li>
                            </ul>
                          </li>
                          <li {if $smarty.get.action|default:"0" eq "wallet" || $smarty.get.action|default:"0" eq "transactions"}class="active"{/if}>
                            <a href="#"><i class="fa fa-usd fa-fw"></i> {t}Funds{/t} <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet"><i class="fa fa-money fa-fw"></i> {t}Wallet Info{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=transactions"><i class="fa fa-tasks fa-fw"></i> {t}Transactions{/t}</a></li>
                            </ul>
                          </li>
                          <li {if $smarty.get.action|default:"0" eq "news" || $smarty.get.action|default:"0" eq "newsletter"}class="active"{/if}>
                            <a href="#"><i class="fa fa-info fa-fw"></i> {t}News{/t} <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=news"><i class="fa fa-list-alt fa-fw"></i> {t}Site News{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=newsletter"><i class="fa fa-list-alt fa-fw"></i> {t}Newsletter{/t}</a></li>
                            </ul>
                          </li>
                          <li {if $smarty.get.action|default:"0" eq "user" || $smarty.get.action|default:"0" eq "reports" || $smarty.get.action|default:"0" eq "registrations" || $smarty.get.action|default:"0" eq "invitations" || $smarty.get.action|default:"0" eq "poolworkers"}class="active"{/if}>
                            <a href="#"><i class="fa fa-users fa-fw"></i> {t}Users{/t} <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=user"><i class="fa fa-user fa-fw"></i> {t}User Info{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=reports"><i class="fa fa-list-ol fa-fw"></i> {t}Reports{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=registrations"><i class="fa fa-pencil-square-o fa-fw"></i> {t}Registrations{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=invitations"><i class="fa fa-users fa-fw"></i> {t}Invitations{/t}</a></li>
                              <li><a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=poolworkers"><i class="fa fa-desktop fa-fw"></i> {t}Pool Workers{/t}</a></li>
                            </ul>
                          </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    {/if}
                    <li {if $smarty.get.page|default:"0" eq "statistics"}class="active"{/if}>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> {t}Statistics{/t}<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          {acl_check page='statistics' action='pool' namehtml='<i class="fa fa-align-left fa-fw"></i> ' name='Pool' acl=$GLOBAL.acl.pool.statistics fallback='page=statistics'}
                          {acl_check page='statistics' action='blocks' namehtml='<i class="fa fa-th-large fa-fw"></i> ' name='Blocks' acl=$GLOBAL.acl.block.statistics}
                          {acl_check page='statistics' action='round' namehtml='<i class="fa fa-refresh fa-fw"></i> ' name='Round' acl=$GLOBAL.acl.round.statistics}
                          {acl_check page='statistics' action='blockfinder' namehtml='<i class="fa fa-search fa-fw"></i> ' name='Blockfinder' acl=$GLOBAL.acl.blockfinder.statistics}
                          {acl_check page='statistics' action='uptime' namehtml='<i class="fa fa-clock-o fa-fw"></i> ' name='Uptime' acl=$GLOBAL.acl.uptime.statistics}
                          {acl_check page='statistics' action='graphs' namehtml='<i class="fa fa-signal fa-fw"></i> ' name='Graphs' acl=$GLOBAL.acl.graphs.statistics}
                          {acl_check page='statistics' action='donors' namehtml='<i class="fa fa-bitbucket fa-fw"></i> ' name='Donors' acl=$GLOBAL.acl.donors.page}
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li {if $smarty.get.page|default:"0" eq "gettingstarted" || $smarty.get.page|default:"0" eq "about"}class="active"{/if}>
                        <a href="#"><i class="fa fa-question fa-fw"></i> {t}Help{/t}<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=gettingstarted"><i class="fa fa-question fa-fw"></i> {t}Getting Started{/t}</a></li>
                          {acl_check page='about' action='pool' namehtml='<i class="fa fa-info fa-fw"></i> ' name='About' acl=$GLOBAL.acl.about.page}
                          {acl_check page='about' action='chat' namehtml='<i class="fa fa-comments-o fa-fw"></i> ' name='Web Chat' acl=$GLOBAL.acl.chat.page}
                          {acl_check page='about' action='moot' namehtml='<i class="fa fa-ticket fa-fw"></i> ' name='Forum' acl=$GLOBAL.acl.moot.forum}
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li {if $smarty.get.page|default:"0" eq "register" || $smarty.get.page|default:"0" eq "login" || $smarty.get.page|default:"0" eq "logout" || $smarty.get.page|default:"0" eq "tac" || $smarty.get.page|default:"0" eq "contactform"}class="active"{/if}>
                        <a href="#"><i class="fa fa-tasks fa-fw"></i> {t}Other{/t}<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                          {if $smarty.session.AUTHENTICATED|default:"0" == 1}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=logout"><i class="fa fa-sign-out fa-fw"></i> {t}Logout{/t}</a></li>
                          {else}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=login"><i class="fa fa-sign-in fa-fw"></i> {t}Login{/t}</a></li>
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=register"><i class="fa fa-pencil fa-fw"></i> {t}Sign Up{/t}</a></li>
                          {/if}
                          {acl_check page='contactform' action='' namehtml='<i class="fa fa-envelope fa-fw"></i> ' name='Contact' acl=$GLOBAL.acl.contactform}
                          <li><a href="{$smarty.server.SCRIPT_NAME}?page=tac"><i class="fa fa-book fa-fw"></i> {t}Terms and Conditions{/t}</a></li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>
        <!-- /.navbar-static-side -->
