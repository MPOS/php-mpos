
        {if $smarty.session.AUTHENTICATED|default:"0" == 1}
        <a href="#left-sidebar" data-icon="arrow-l" data-iconpos="notext" data-shadow="false" data-iconshadow="false" class="ui-icon-nodisc">Left Sidebar</a>
        {/if}
        <div data-role="navbar">
          <ul>
            {if $smarty.session.AUTHENTICATED|default:"0" == 1}
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=dashboard" data-icon="grid" data-ajax="false">Dashboard</a></li>
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=account&action=workers" data-icon="grid" data-ajax="false">Worker</a></li>
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=statistics&action=pool" data-icon="grid" data-ajax="false">Statistics</a></li>
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=logout" data-icon="gear" data-ajax="false">Logout</a></li>
            {else}
            <li><a href="{$smarty.server.SCRIPT_NAME}" data-icon="info" data-ajax="false">News</a></li>
            <li><a href="{$smarty.server.SCRIPT_NAME}?page=login" data-icon="gear" data-ajax="false">Login</a></li>
            {/if}
          </ul>
        </div>
