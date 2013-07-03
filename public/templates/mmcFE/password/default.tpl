{include file="global/block_header.tpl" BLOCK_HEADER="Reset Password" BLOCK_STYLE="clear:none;"}
<form action="" method="POST">
<input type="hidden" name="page" value="password">
<input type="hidden" name="action" value="reset">
  <p>If you have an email set for your account, enter your username to get your password reset</p>
  <p><input type="text" value="{$smarty.post.username|default:""}" name="username" required><input class="submit small" type="submit" value="Reset"></p>
</form>
{include file="global/block_footer.tpl"}
