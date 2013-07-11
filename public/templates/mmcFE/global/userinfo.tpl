{if $GLOBAL.userdata.username|default}
            <h2>Welcome, {$smarty.session.USERDATA.username|escape} <font size='1px'><b>Active Account</b>: <b>{$GLOBAL.fees|escape}%</b> Pool Fee</font> <font size='1px'><i>(You are <a href='{$smarty.server.PHP_SELF}?page=account&action=edit'>donating</a> <b></i>{$GLOBAL.userdata.donate_percent|escape}%</b> <i>of your earnings)</i></font></h2>
{else}
            <h2>Welcome guest, <font size="1px"> please <a href="{$smarty.server.PHP_SELF}?page=register">register</a> to user this pool.</font></h2>
{/if}
