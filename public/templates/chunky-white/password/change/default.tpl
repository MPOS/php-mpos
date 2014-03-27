<article class="module width_half">
  <form action="{$smarty.server.PHP_SELF}" method="post" class="form-horizontal">
    <input type="hidden" name="token" value="{$smarty.request.token|escape}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="resetPassword">
    <header><h3>Password reset</h3></header>
    <div class="module_content">
      <fieldset>
        <div class="control-group">
            <label class="control-label" for="password-field">New Password</label>
            <div class="controls form-group">
                <div class="input-group col-sm-8">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="newPassword" type="password" class="form-control" id="password-field-1" placeholder="New Password" required>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="password-field">Repeat New Password</label>
            <div class="controls form-group">
                <div class="input-group col-sm-8">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="newPassword2" type="password" class="form-control" id="password-field-2" placeholder="Repeat New Password" required>
                </div>
            </div>
        </div>
      </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Change Password and PIN" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
