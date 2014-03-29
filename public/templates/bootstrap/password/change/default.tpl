<article class="module width_half">
  <form action="{$smarty.server.SCRIPT_NAME}" method="post">
    <input type="hidden" name="token" value="{$smarty.request.token|escape}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
    
    <input type="hidden" name="do" value="resetPassword">
    <header><h3>Password reset</h3></header>
    <div class="module_content">
      <fieldset>
        <label>New Password</label>
        <input type="password" name="newPassword" required>
      </fieldset>
      <fieldset>
        <label>Repeat New Password</label>
        <input type="password" name="newPassword2" required>
      </fieldset>
      <div class="clear"></div>
    </div>
    <footer>
      {nocache}
        <input type="hidden" name="cp_token" value="{$smarty.request.cp_token|escape|default:""}">
        <input type="hidden" name="utype" value="change_pw">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw}
          {if $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 1}
          	<input type="submit" value="Change Password" class="btn btn-warning btn-sm">
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 1 || $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Change Password" class="btn btn-warning btn-sm" disabled="disabled">
          {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 0}
            <input type="submit" value="Unlock" class="btn btn-warning btn-sm" name="unlock">
          {/if}
        {else}
          <input type="submit" value="Change Password" class="btn btn-warning btn-sm">
        {/if}
      {/nocache}
    </footer>
  </form>
</article>
