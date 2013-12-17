**An error occured!**

This should never happen. Please review the error output below.

{nocache}
  {foreach from=$DATA key=text item=message}
    {if $text != 'email' && $text != 'subject'}
      * {$message}
    {/if}
  {/foreach}
{/nocache}