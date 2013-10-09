{include file="global/block_header.tpl" BLOCK_HEADER="My Workers"}
<center>
  <form action="{$smarty.server.PHP_SELF}" method="post">
    <input type="hidden" name="page" value="{$smarty.request.page}">
    <input type="hidden" name="action" value="{$smarty.request.action}">
    <input type="hidden" name="do" value="update">
    <table border="0" cellpadding="3" cellspacing="3">
      <tbody>
      <tr>
        <th>Worker Name</th>
        <th>Password</th>
        <th class="center">Active</th>
        {if $GLOBAL.config.disable_notifications != 1}<th class="center">Monitor</th>{/if}
        <th class="right">Khash/s</th>
        <th class="right">Difficulty</th>
        <th>Action</th>
      </tr>
      {nocache}
      {section worker $WORKERS}
      {assign var="username" value="."|escape|explode:$WORKERS[worker].username:2} 
      <tr>
        <td{if $WORKERS[worker].hashrate > 0} style="color: orange"{/if}>{$username.0|escape}.<input name="data[{$WORKERS[worker].id}][username]" value="{$username.1|escape}" size="10" required/></td>
        <td><input type="text" name="data[{$WORKERS[worker].id}][password]" value="{$WORKERS[worker].password|escape}" size="10" required></td>
        <td class="center"><img src="{$PATH}/images/{if $WORKERS[worker].hashrate > 0}success{else}error{/if}.gif" /></td>
        {if $GLOBAL.config.disable_notifications != 1}
        <td class="center">
          <input type="checkbox" name="data[{$WORKERS[worker].id}][monitor]" value="1" id="data[{$WORKERS[worker].id}][monitor]" {if $WORKERS[worker].monitor}checked{/if} />
          <label for="data[{$WORKERS[worker].id}][monitor]"></label>
        </td>
        {/if}
        <td class="right">{$WORKERS[worker].hashrate|number_format}</td>
        <td class="right">{$WORKERS[worker].difficulty|number_format:"2"}</td>
        <td align="right"><a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$WORKERS[worker].id|escape}"><button style="padding:5px" type="button">Delete</button></a></td>
      </tr>
      {/section}
      {/nocache}
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
    {$smarty.session.USERDATA.username}.<input type="text" name="username" value="user" size="10" maxlength="20" required> · <input type="text" name="password" value="password" size="10" maxlength="20" required>&nbsp;<input type="submit" value="Add New Worker" style="padding:5px;">
  </form>
</center>
{include file="global/block_footer.tpl"}
