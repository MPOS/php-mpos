{if $GLOBAL.userdata.username}
            <h2>Welcome, {$smarty.session.USERDATA.username} <font size='1px'><b>Active Account</b>: <b>0%</b> Pool Fee</font> <font size='1px'><i>(You are <a href='/osList'>donating</a> <b></i>{$GLOBAL.userdata.donate_percent}%</b> <i>of your earnings)</i></font></h2>
{else}
            <h2>Welcome guest, <font size="1px"> please <a href="{$smarty.server.PHP_SELF}?page=register">register</a> to user this pool.</font></h2>
{/if}
