<div class="overlaymenu">
  {if $smarty.session.AUTHENTICATED|default:"0" == 1}
  <header>
    <div class="pull-right">
      <ul id="mini-nav" class="clearfix">
        {if $smarty.session.AUTHENTICATED|default:"0" == 1 && $GLOBAL.userdata.lastnotifications|@count|default:"0" != 0}
        <li class="list-box dropdown">
          <a id="drop5" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bullhorn"></i>
          </a>
            <span class="info-label warning-bg">{$GLOBAL.userdata.lastnotifications|@count|default:"0"}</span>
            <ul class="dropdown-menu server-activity">
              {section notification $GLOBAL.userdata.lastnotifications}
              <li>
                <p>
                  {if $GLOBAL.userdata.lastnotifications[notification].type == new_block}<i class="fa fa-th-large fa-fw text-info"></i> New Block
                  {else if $GLOBAL.userdata.lastnotifications[notification].type == payout}<i class="fa fa-money fa-fw text-info"></i> Payout
                  {else if $GLOBAL.userdata.lastnotifications[notification].type == idle_worker}<i class="fa fa-desktop fa-fw text-warning"></i> IDLE Worker
                  {else if $GLOBAL.userdata.lastnotifications[notification].type == success_login}<i class="fa fa-sign-in fa-fw text-success"></i> Successful Login
                  {/if}
                  <span class="time">{$GLOBAL.userdata.lastnotifications[notification].time|relative_date}</span></p>
              </li>
              {/section}
            </ul>
        </li>
        {/if}
        <li class="list-box user-profile">
          <a id="drop7" href="#" role="button" class="dropdown-toggle user-avtar" data-toggle="dropdown">
            <img src="{$PATH}/img/profile.png" alt="logged in user">
          </a>
          <ul class="dropdown-menu server-activity">
            <li>
              <p id="header_dashboard"><i title="" data-original-title="" class="fa fa-dashboard text-info"></i> Dashboard</p>
            </li>
            <li>
              <p id="header_accountsettings"><i title="" data-original-title="" class="fa fa-gear text-info"></i> Settings</p>
            </li>
            <li>
              <p id="header_workers"><i title="" data-original-title="" class="fa fa-desktop text-info"></i> Workers</p>
            </li>
            <li>
              <div class="demo-btn-group clearfix">
                <button id="header_logout" class="btn btn-danger">Logout</button>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </header>
  {/if}
</div>


<script>
{literal}
$(document).ready(function(){
  $('#header_dashboard').click(function(){
    window.location.href = "{/literal}{$smarty.server.SCRIPT_NAME}{literal}?page=dashboard";
  });
  $('#header_accountsettings').click(function(){
    window.location.href = "{/literal}{$smarty.server.SCRIPT_NAME}{literal}?page=account&action=edit";
  });
  $('#header_workers').click(function(){
    window.location.href = "{/literal}{$smarty.server.SCRIPT_NAME}{literal}?page=account&action=workers";
  });
  $('#header_logout').click(function(){
    window.location.href = "{/literal}{$smarty.server.SCRIPT_NAME}{literal}?page=logout";
  });
});
{/literal}
</script>