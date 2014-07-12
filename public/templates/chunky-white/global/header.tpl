<header class="page-header">
  <div class="navbar">
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      <ul class="nav navbar-nav navbar-right pull-right">
        <li class="divider"></li>
        <li class="hidden-xs">
            <a href="/account" id="settings" title="" data-toggle="popover" data-placement="bottom" data-original-title="Settings">
                <i class="fa fa-cog"></i>
            </a>
        </li>
        <li class="hidden-xs"><a href="/logout"><i class="fa fa-sign-out"></i></a></li>
      </ul>
      <div class="user-header pull-right"><strong>{$GLOBAL.userdata.username|escape}</strong></div>
    {else}
      <div>
        <form action="{$smarty.server.PHP_SELF}" method="post" id="loginForm" class="navbar-form pull-right">
          <input type="hidden" name="page" value="login" />
          <input type="hidden" name="to" value="{$smarty.server.PHP_SELF}?page=dashboard" />

          <div class="row">
            <div class="input-group small-login">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input class="login2" type="text" name="username" size="22" maxlength="100" placeholder="e-mail" required />
            </div>

            <div class="input-group small-login">
              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input class="login2" type="password" name="password" size="22" maxlength="100" placeholder="password" required />
            </div>

            <input type="submit" value="Login" class="align-middle btn btn-primary" />
          </div>
        </form>
      </div>
    {/if}
      <div class="notifications pull-right">
        <div class="alert pull-right">
          <i class="fa fa-info-circle"></i> 
          Just added <a href="https://chunkypools.com/rzr/" target="_blank">Razor</a> to Chunky Pools!
        </div>
      </div>
  </div>
</header>

