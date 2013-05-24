          <ul id="nav">
            <li><a href="{$smarty.server.PHP_SELF}">Home</a></li>
            {if $smarty.session.AUTHENTICATED == 1}
            <li><a href="">My Account</a>
              <ul>
                <li><a href="{$smarty.server.PHP_SELF}?page=account&action=edit">Edit Account</a></li>
                <li><a href="{$smarty.server.PHP_SELF}?page=account&action=workers">My Workers</a></li>
                <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=user">My Graphs</a></li>
                <li><a href="{$smarty.server.PHP_SELF}?page=account&action=transactions">Transactions</a></li>
              </ul>
            </li>
            {/if}
            {if $smarty.session.AUTHENTICATED == 1 && $GLOBAL.userdata.admin == 1}<li><a href="#">Admin Panel</a></li>{/if}
            <li><a href="{$smarty.server.PHP_SELF}?page=statistics">Statistics</a>
              <ul>
                <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool">Pool Stats</a></li>
                {if $smarty.session.AUTHENTICATED}<li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=blocks">Block Stats</a></li>{/if}
              </ul>
            </li>
            <li><a href="{$smarty.server.PHP_SELF}?page=gettingstarted">Getting Started</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=support">Support</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=about&action=pplns">About</a>
              <ul>
                <li><a href="{$smarty.server.PHP_SELF}?page=about&action=pplns">PPLNS Payout</a></li>
                <li><a href="{$smarty.server.PHP_SELF}?page=about&action=pool">This Pool</a></li>
              </ul>
            </li>
            <li><a href="{$smarty.server.PHP_SELF}?page=news">News</a></li>
            {if $smarty.session.AUTHENTICATED == 1}<li><a href="{$smarty.server.PHP_SELF}?page=logout">Logout</a></li>{else}<li><a href="{$smarty.server.PHP_SELF}?page=register">Register</a></li>{/if}
          </ul>
