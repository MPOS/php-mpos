    <div class="user">
{if $GLOBAL.userdata.username|default}
            <p>{$smarty.session.USERDATA.username|escape}</p>
{else}
            <p>Guest</p>
{/if}
    </div>
