    <hr/>
    <li class="icon-home"><a href="{$smarty.server.PHP_SELF}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>My Account</h3>
    <ul class="toggle">
    <li class="icon-gauge"><a href="{$smarty.server.PHP_SELF}?page=dashboard">Dashboard</a></li>
    <li class="icon-user"><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Edit Account</a></li>
    <li class="icon-photo"><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">My Workers</a></li>
    <li class="icon-indent-left"><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transactions</a></li>
    {if !$GLOBAL.config.disable_notifications}<li class="icon-mail"><a href="{$smarty.server.PHP_SELF}?page=account&action=notifications">Notifications</a></li>{/if}
    {if !$GLOBAL.config.disable_invitations}<li class="icon-plus"><a href="{$smarty.server.PHP_SELF}?page=account&action=invitations">Invitations</a></li>{/if}
    <li class="icon-barcode"><a href="{$smarty.server.PHP_SELF}?page=account&action=qrcode">QR Codes</a></li>
    </ul>
    </li>
    {/if}
    <h3>Statistics</h3>
    <ul class="toggle">
      <li class="icon-align-left"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
      <li class="icon-th-large"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Blocks</a></li>
      <li class="icon-chart"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=graphs">Graphs</a></li>
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      <li class="icon-cancel"><a href="{$smarty.server.PHP_SELF}?page=logout">Logout</a></li>
      {else}
      <li class=""><a href="{$smarty.server.PHP_SELF}?page=login">Login</a></li>
      {/if}
    </ul>

