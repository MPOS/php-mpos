    <div class="user">
{if $GLOBAL.userdata.username|default}
            <p>Willkommen {$smarty.session.USERDATA.username|escape}</p>
{else}
            <p>Willkommen Gast</p>
{/if}
    </div>
