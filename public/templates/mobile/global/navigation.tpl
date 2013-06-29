        <div data-role="navbar">
          <ul>
            {if $smarty.session.AUTHENTICATED|default:"0" == 1}
            <li><a href="{$smarty.server.PHP_SELF}" data-icon="home" data-ajax="false">Dash</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=news" data-icon="info" data-ajax="false">News</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=statistics&action=pool" data-icon="grid" data-ajax="false">Statistics</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=logout" data-icon="gear" data-ajax="false">Logout</a></li>
            {else}
            <li><a href="{$smarty.server.PHP_SELF}" data-icon="info" data-ajax="false">News</a></li>
            <li><a href="{$smarty.server.PHP_SELF}?page=login" data-icon="gear" data-ajax="false">Login</a></li>
            {/if}
          </ul>
        </div>
