{if $SHOW_BROADCAST}
    <form method="get" action="{$smarty.server.PHP_SELF}">
        <input type="hidden" name="page" value="account">
        <input type="hidden" name="action" value="inbox">
        <input type="hidden" name="do" value="send">
        <input type="hidden" name="account_id" value="0">
        <div class="submit_link">
            <input type="submit" value="Broadcast Message" class="alt_btn" title="Send a message to all users.">
        </div>
    </form>
{/if}
{section name=messages loop=$MESSAGES}
    <article class="module width_full">
        <header>
            <h3>
                {$MESSAGES[messages].subject},
                <font size=\"1px\">
                    sent {$MESSAGES[messages].time|date_format:"%b %e, %Y at %H:%M"}
                    {if $MESSAGES[messages].account_id_from != 0}
                    by <b>{$MESSAGES[messages].username}</b>
                    {/if}
                </font>
            </h3>
        </header>
        <div class="module_content">
            {$MESSAGES[messages].content}
            <div class="clear"></div>
        </div>
        <form action="{$smarty.server.PHP_SELF}" method="get">
            <input type="hidden" name="page" value="{$smarty.request.page|escape}">
            <input type="hidden" name="action" value="inbox">
            <input type="hidden" name="message_id" value="{$MESSAGES[messages].id}">
            <footer>
                <div class="submit_link">
                    <input type="submit" name="do" value="Delete" class="alt_btn">
                    <input type="submit" name="do" value="Reply" class="alt_btn">
                </div>
            </footer>
        </form>
    </article>
{/section}