{if $GLOBAL.userdata.username|default}
            <h2>Welcome, {$smarty.session.USERDATA.username|escape} <font size='1px'><b>{if $GLOBAL.userdata.is_anonymous}Anonymous{else if $GLOBAL.userdata.no_fees}Promo{else}Active{/if} Account</b>: <b>{if $GLOBAL.userdata.no_fees}0{else}{$GLOBAL.fees|escape}{/if}%</b> Pool Fee</font> <font size='1px'><i>(You are <a href='{$smarty.server.PHP_SELF}?page=account&action=edit'>donating</a> <b></i>{$GLOBAL.userdata.donate_percent|escape}%</b> <i>of your earnings)</i></font></h2>
{else}
            <h2>Welcome guest, <font size="1px"> please <a href="{$smarty.server.PHP_SELF}?page=register">register</a> to user this pool.</font></h2>
{/if}
