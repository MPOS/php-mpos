{if $smarty.session.AUTHENTICATED|default:"0" == 0}
<div>
  <form action="{$smarty.server.PHP_SELF}" method="post" id="loginForm" class="navbar-form pull-right">
    <input type="hidden" name="page" value="login" />
    <input type="hidden" name="to" value="{$smarty.server.PHP_SELF}?page=dashboard" />
    <fieldset2 class="small">
      <input class="search-query" type="text" name="username" size="22" maxlength="100" placeholder="username" required />
      <fieldset2 class="small">
        <input class="search-query" type="password" name="password" size="22" maxlength="100" placeholder="password" required />
      </fieldset2>
    </fieldset2>
    <input type="submit" value="Login" class="align-middle btn btn-primary" />
  </form>
</div>
{/if}
