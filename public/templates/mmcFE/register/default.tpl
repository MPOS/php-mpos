{include file="global/block_header.tpl" BLOCK_HEADER="Join our pool" BLOCK_STYLE="clear:none;"}
<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
{if $smarty.request.token|default:""}
  <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
{/if}
  <input type="hidden" name="action" value="register">
  <table width="90%" border="0">
    <tbody>
      <tr><td>Username:</td><td><input type="text" class="text tiny" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required></td></tr>
      <tr><td>Password:</td><td><input type="password" class="text tiny" name="password1" value="" size="15" maxlength="20" required></td></tr>
      <tr><td>Repeat Password:</td><td><input type="password" class="text tiny" name="password2" value="" size="15" maxlength="20" required></td></tr>
      <tr><td>Email:</td><td><input type="text" name="email1" class="text small" value="{$smarty.post.email1|escape|default:""}" size="15" required></td></tr>
      <tr><td>Email Repeat:</td><td><input type="text" class="text small" name="email2" value="{$smarty.post.email2|escape|default:""}" size="15" required></td></tr>
      <tr><td>PIN:</td><td><input type="password" class="text pin" name="pin" value="" size="4" maxlength="4"><font size="1"> (4 digit number. <b>Remember this pin!</b>)</font></td></tr>
      <tr><td colspan="2">{nocache}{$RECAPTCHA|default:""}{/nocache}</td></tr>
      <tr><td class="center"><input type="submit" class="submit small" value="Register"></td><td></td></tr>
    </tbody>
  </table>
</form>
{include file="global/block_footer.tpl"}
