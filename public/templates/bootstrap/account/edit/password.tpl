<form action="{$smarty.server.SCRIPT_NAME}" role="form" method="post"><input type="hidden" name="act" value="updatePassword">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updatePassword">
  <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-key fa-fw"></i> Change Password
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
                Note: You will be redirected to login on successful completion of a password change
              </p>
            </div>
            <div class="form-group">
              <label>Current Password</label>
              {nocache}<input class="form-control" type="password" name="currentPassword" {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}id="disabledInput" disabled{/if}/>{/nocache}
            </div>
            <div class="form-group">
              <label>New Password</label>
              <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength"></p>
              {nocache}<input class="form-control" type="password" name="newPassword" id="pw_field"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}id="disabledInput" disabled{/if}/>{/nocache}
            </div>
            <div class="form-group">
              <label>Repeat New Password</label>
              <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
              {nocache}<input class="form-control" type="password" name="newPassword2" id="pw_field2"{if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw && !$CHANGEPASSUNLOCKED}id="disabledInput" disabled{/if}/>{/nocache}
            </div>
            <div class="form-group">
              <label>4 Digit PIN</label>
              <input class="form-control" type="password" name="authPin" size="4" maxlength="4" />
            </div>

          </div>
        </div>
      </div>
      <div class="panel-footer">
        {nocache}
        <input type="hidden" name="cp_token" value="{$smarty.request.cp_token|escape|default:""}">
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <input type="hidden" name="utype" value="change_pw">
        {if $GLOBAL.twofactor.enabled && $GLOBAL.twofactor.options.changepw}
        {if $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 1}
        <input type="submit" value="Change Password" class="btn btn-success btn-sm">
        {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 1 || $CHANGEPASSSENT == 1 && $CHANGEPASSUNLOCKED == 0}
        <input type="submit" value="Change Password" class="btn btn-danger btn-sm" disabled>
        {elseif $CHANGEPASSSENT == 0 && $CHANGEPASSUNLOCKED == 0}
        <input type="submit" value="Unlock" class="btn btn-warning btn-sm" name="unlock">
        {/if}
        {else}
        <input type="submit" value="Change Password" class="btn btn-success btn-sm">
        {/if}
        {/nocache}
      </div>
    </div>
  </div>
</form>