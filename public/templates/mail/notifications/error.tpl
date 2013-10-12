<html>
<body>
<h1>An error occured!</h1>
<p>This should never happen. Please review the error output below.</p>

{foreach from=$DATA key=text item=message}
  {if $text != 'email' && $text != 'subject'}
    <p>{$text}: {$message}</p>
  {/if}
{/foreach}
