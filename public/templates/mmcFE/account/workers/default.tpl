{include file="global/block_header.tpl" BLOCK_HEADER="My Workers"}
<ul><li><font color="red">
    CAUTION! </font>Deletion of a worker could cause all associated shares for that worker to be lost.
  Do not delete Workers unless you are certain all of their shares have been counted or that you have never used that worker account.
</li></ul>

<center>
  <form action="{$smarty.server.PHP_SELF}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page}">
    <input type="hidden" name="action" value="{$smarty.request.action}">
    <input type="hidden" name="do" value="update">
    <table border="0" cellpadding="3" cellspacing="3">
      <tbody>
      <tr>
        <td>Worker Name</td>
        <td>Password</td>
        <td>Active</td>
        <td>Khash/s</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      {section worker $WORKERS}
      {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
      <tr>
        <td{if $WORKERS[worker].active == 1} style="color: orange"{/if}>{$username.0|escape}.<input name="data[{$WORKERS[worker].id}][username]" value="{$username.1|escape}" size="10" /></td>
        <td><input type="text" name="data[{$WORKERS[worker].id}][password]" value="{$WORKERS[worker].password|escape}" size="10"></td>
        <td>{if $WORKERS[worker].active == 1}Y{else}N{/if}</td>
        <td>{$WORKERS[worker].hashrate}</td>
        <td align="right"><a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$WORKERS[worker].id|escape}"><button style="padding:5px" type="button">Delete</button></a></td>
      </tr>
      {/section}
      </tbody>
    </table>
    <input type="submit" value="Update Workers" style="padding:5px">
  </form>
</center>

<br/>
<br/>
<center>
  <h2>Add a New Worker</h2>
  <form action="{$smarty.server.PHP_SELF}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page}">
    <input type="hidden" name="action" value="{$smarty.request.action}">
    <input type="hidden" name="do" value="add">
    TheSerapher.<input type="text" name="username" value="user" size="10" maxlength="20"> · <input type="text" name="password" value="password" size="10" maxlength="20">&nbsp;<input type="submit" value="Add New Worker" style="padding:5px;">
  </form>
</center>
{include file="global/block_footer.tpl"}
