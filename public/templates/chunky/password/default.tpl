<article class="module width_half">
  <form action="" method="POST" class="form-horizontal">
    <input type="hidden" name="page" value="password">
    <input type="hidden" name="action" value="reset">
    <header><h3>Password reset</h3></header>
    <div class="module_content">
      <p>If you have an email set for your account, enter your username to get your password reset</p>
      <fieldset>
        <label>Username or E-Mail</label>
        <input type="text" name="username" value="{$smarty.post.username|escape|default:""}" size="22" required>
      </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Reset" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
