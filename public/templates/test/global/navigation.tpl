    <hr/>
    <li><a href="{$smarty.server.PHP_SELF}">Home</a></li>
    {if $smarty.session.AUTHENTICATED|default:"0" == 1}
    <h3>My Account</h3>
    <ul class="toggle">
    <li class="icn_profile"><a href="{$smarty.server.PHP_SELF}?page=dashboard">Dashboard</a></li>
    <li class="icn_profile"><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Edit Account</a></li>
    <li class="icn_edit_article"><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">My Workers</a></li>
    <li class="icn_categories"><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transactions</a></li>
    {if !$GLOBAL.config.disable_notifications}<li class="icn_categories"><a href="{$smarty.server.PHP_SELF}?page=account&action=notifications">Notifications</a></li>{/if}
    {if !$GLOBAL.config.disable_invitations}<li class="icn_categories"><a href="{$smarty.server.PHP_SELF}?page=account&action=invitations">Invitations</a></li>{/if}
    <li class="icn_tags"><a href="{$smarty.server.PHP_SELF}?page=account&action=qrcode">QR Codes</a></li>
    </ul>
    </li>
    {/if}
    <h3>Statistics</h3>
    <ul class="toggle">
      <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool</a></li>
      <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Blocks</a></li>
      <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=graphs">Graphs</a></li>
    </ul>
    <h3>Other</h3>
    <ul class="toggle">
      <li class="icn_settings"><a href="#">Options</a></li>
      <li class="icn_security"><a href="#">Security</a></li>
      {if $smarty.session.AUTHENTICATED|default:"0" == 1}
      <li class="icn_jump_back"><a href="{$smarty.server.PHP_SELF}?page=logout">Logout</a></li>
      {else}
      <li class="icn_jump_back"><a href="{$smarty.server.PHP_SELF}?page=login">Login</a></li>
      {/if}
    </ul>

