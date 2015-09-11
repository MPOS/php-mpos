<script>
  var zxcvbnPath = "{$PATH}/js/zxcvbn/zxcvbn.js";
</script>
<script type="text/javascript" src="{$PATH}/js/pwcheck.js"></script>

<div class="row">
  <div class="col-lg-5">
    <form class="panel panel-info" method="post" role="form">
      <input type="hidden" name="page" value="{$smarty.request.page|escape}">
      {if $smarty.request.token|default:""}
      <input type="hidden" name="token" value="{$smarty.request.token|escape}">
      {/if}
      <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}">
      <input type="hidden" name="action" value="register">
      <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i> {t}Register new account{/t}
      </div>
      <div class="panel-body">
        <div class="form-group">
          <label>{t}Username{/t}</label>
          <div class="input-group  input-group-sm">
            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{$smarty.post.username|escape|default:""}" size="15" maxlength="20" required>
          </div>
          {if $GLOBAL.coinaddresscheck|default:"1"}
          <label>{t}Coin Address{/t}</label>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-money fa-fw"></i></span>
            <input type="text" name="coinaddress" placeholder="Coin Address" class="form-control" value="{$smarty.post.coinaddress|escape|default:""}" size="15" required>
          </div>
          {/if}
          <label>{t}Password{/t}</label> (<span id="pw_strength">{t}Strength{/t}</span>)
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
            <input type="password" class="form-control" name="password1" placeholder="Password" value="" size="15" maxlength="100" id="pw_field" required>
          </div>
          <span id="pw_match"></span>
          <div class="input-group input-group-sm">
            <span class="input-group-addon" id="pw_match"><i class="fa fa-key fa-fw"></i></span>
            <input type="password" class="form-control" name="password2" placeholder="Repeat Password" value="" size="15" maxlength="100" id="pw_field2" required>
          </div>
          <label>{t}Email{/t}</label>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input type="text" name="email1" placeholder="Email" class="form-control" value="{$smarty.post.email1|escape|default:""}" size="15" required>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
            <input type="text" class="form-control" name="email2" placeholder="Repeat Email" value="{$smarty.post.email2|escape|default:""}" size="15" required>
          </div>
          <label>{t}PIN{/t}</label>
          <font size="1">{t}Four digit number.{/t} <b>{t}Remember this pin!{/t}</b></font>
          <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="fa fa-shield fa-fw"></i></span>
            <input type="password" class="form-control" name="pin" placeholder="PIN" value="" size="4" maxlength="4" required>   
          </div>
          <div class="input-group input-group-sm">
            <label>
              <input type="checkbox" value="1" name="tac" id="tac"> {t}I Accept The{/t} <a data-toggle="modal" data-target="#TAC">{t}Terms and Conditions{/t}</a>
            </label>
          </div>
        </div>
        <center>{nocache}{$RECAPTCHA|default:"" nofilter}{/nocache}</center>
      </div>
      <div class="panel-footer">
        <input type="submit" value="{t}Register{/t}" class="btn btn-success btn-sm">
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="TAC" tabindex="-1" role="dialog" aria-labelledby="TACLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="TACLabel">{t}Terms and Conditions{/t}</h4>
        </div>
        <div class="modal-body">
          {include file="tac/content.tpl"}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">{t}Close{/t}</button>
        </div>
      </div>
    </div>
  </div>
</div>
