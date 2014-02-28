<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Register new account
      </div>
      <div class="panel-body">
        <form action="{$smarty.server.SCRIPT_NAME}" method="post" role="form">
          <input type="hidden" name="page" value="{$smarty.request.page|escape}">
          {if $smarty.request.token|default:""}
          <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
          {/if}
          <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
          <input type="hidden" name="action" value="register">
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
          </div>
          <div class="form-group">
            <label>Password</label> 
            <p style="padding-right:10px;display:block;margin-top:0px;float:right;color:#999;" id="pw_strength">Strength</p>
            <input type="password" class="form-control" name="password1" value="" size="15" maxlength="100" id="pw_field" required>
            <label>Repeat Password</label>
            <p style="padding-right:10px;display:block;margin-top:0px;float:right;" id="pw_match"></p>
            <input type="password" class="form-control" name="password2" value="" size="15" maxlength="100" id="pw_field2" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="email1" class="form-control" value="{$smarty.post.email1|escape|default:""}" size="15" required>
            <label>Email Repeat</label>
            <input type="text" class="form-control" name="email2" value="{$smarty.post.email2|escape|default:""}" size="15" required>
          </div>
          <div class="form-group">
            <label>PIN</label>
            <input type="password" class="form-control" name="pin" value="" size="4" maxlength="4"><font size="1"> (4 digit number. <b>Remember this pin!</b>)</font>
          </div>
          <div class="form-group">
            <label>TERMS AND CONDITIONS</label>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="1" name="tac" id="tac"><button type="button" class="btn btn-outline btn-link" data-toggle="modal" data-target="#TAC">Accept Terms and Conditions</button>
              </label>
            </div>
          </div>

            <div class="panel-body">
              <!-- Button trigger modal -->
              <!-- Modal -->
              <div class="modal fade" id="TAC" tabindex="-1" role="dialog" aria-labelledby="TACLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="TACLabel">Terms and Conditions</h4>
                    </div>
                    <div class="modal-body">
                      {include file="tac/default.tpl"}
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                <!-- /.modal-content -->
                </div>
              <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
            </div>
          </div>
          <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
          <input type="submit" value="Register" class="btn btn-outline btn-success btn-lg btn-block">
        </form>
      </div>
    </div>
  </div>
</div>