<form action="" method="POST">
<input type="hidden" name="page" value="password">
<input type="hidden" name="action" value="reset">
<input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <p>If you have an email set for your account, enter your username to get your password reset</p>
  <p><input type="text" value="{$smarty.post.username|escape|default:""}" name="username" required><input class="submit small" type="submit" value="Reset"></p>
</form>
