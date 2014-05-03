  {include file="../global/header.tpl"}
  <h1>An error occured!</h1>
  <p>This should never happen. Please review the error output below.</p>
  <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
  <tr>
    <td>
    <table cellpadding="0" cellspacing="1" border="0" align="left" width="800px">
{nocache}
{foreach from=$DATA key=text item=message}
  {if $text != 'email' && $text != 'subject'}
      <tr>
        <th align="left" width="150px">{$text}</th>
        <td>{$message}</td>
      </tr>
  {/if}
{/foreach}
{/nocache}
    </table>
    </td>
  </tr>
  </table>
  {include file="../global/footer.tpl"}
