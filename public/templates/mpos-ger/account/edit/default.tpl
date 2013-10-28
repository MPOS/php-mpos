<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updateAccount">
  <article class="module width_half">
    <header><h3>Benutzer Details</h3></header>
    <div class="module_content">
      <fieldset>
        <label>Benutzername</label>
        <input type="text" value="{$GLOBAL.userdata.username|escape}" readonly />
      </fieldset>
      <fieldset>
        <label>Benutzer ID</label>
        <input type="text" value="{$GLOBAL.userdata.id}" readonly />
      </fieldset>
      {if !$GLOBAL.website.api.disabled}
      <fieldset>
        <label>API Schl&uuml;ssel</label>
        <a href="{$smarty.server.PHP_SELF}?page=api&action=getuserstatus&api_key={$GLOBAL.userdata.api_key}&id={$GLOBAL.userdata.id}">{$GLOBAL.userdata.api_key}</a>
      </fieldset>
      {/if}
      <fieldset>
        <label>E-Mail</label>
        <input type="text" name="email" value="{nocache}{$GLOBAL.userdata.email|escape}{/nocache}" size="20" />
      </fieldset>
      <fieldset>
        <label>Auszahlungsadresse</label>
        <input type="text" name="paymentAddress" value="{nocache}{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}{nocache}" size="40" />
      </fieldset>
      <fieldset>
        <label>Spendensatz</label>
        <font size="1"> Spendensatz in Prozent (z.B.: 0.5)</font>
        <input type="text" name="donatePercent" value="{nocache}{$smarty.request.donatePercent|default:$GLOBAL.userdata.donate_percent|escape}{nocache}" size="4" />
      </fieldset>
      <fieldset>
        <label>automatische Auszahlung ab</label>
        <font size="1">{$GLOBAL.config.ap_threshold.min}-{$GLOBAL.config.ap_threshold.max} {$GLOBAL.config.currency}. Auf '0' setzten f&uuml;r keine automatische Auszahlung.</font>
        <input type="text" name="payoutThreshold" value="{$smarty.request.payoutThreshold|default:$GLOBAL.userdata.ap_threshold|escape}" size="5" maxlength="5" />
      </fieldset>
      <fieldset>
        <label>Anonymer Benutzer</label>
        Benutzer auf der Webseite vor anderen Nutzern verstecken. Admins k&ouml;nnen Deinen Benutzer trotzdem sehen.
        <label class="checkbox" for="is_anonymous">
        <input class="ios-switch" type="hidden" name="is_anonymous" value="0" />
        <input class="ios-switch" type="checkbox" name="is_anonymous" value="1" id="is_anonymous" {if $GLOBAL.userdata.is_anonymous}checked{/if} />
        <div class="switch"></div>
        </label>
      </fieldset>
      <fieldset>
        <label>4 stellige PIN</label>
        <font size="1">Die 4 stellige PIN von der Registrierung</font>
        <input type="password" name="authPin" size="4" maxlength="4">
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Aktualisieren" class="alt_btn">
      </div>
    </footer>
  </article>
</form>

{if !$GLOBAL.disable_mp}
<form action="{$smarty.server.PHP_SELF}" method="post">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="cashOut">
  <article class="module width_half">
    <header>
      <h3>Auszahlung</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
        Please note: a {$GLOBAL.config.txfee} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments
      </p>
      <fieldset>
        <label>Kontostand</label>
        <input type="text" value="{nocache}{$GLOBAL.userdata.balance.confirmed|escape}{/nocache}" {$GLOBAL.config.currency} readonly/>
      </fieldset>
      <fieldset>
        <label>Auszahlung an</label>
        <input type="text" value="{nocache}{$GLOBAL.userdata.coin_address|escape}{/nocache}" readonly/>
      </fieldset>
      <fieldset>
        <label>4 stellige PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Auszahlung" class="alt_btn">
      </div>
    </footer>
  </article>
</form>
{/if}

<form action="{$smarty.server.PHP_SELF}" method="post"><input type="hidden" name="act" value="updatePassword">
  <input type="hidden" name="page" value="{$smarty.request.page|escape}">
  <input type="hidden" name="action" value="{$smarty.request.action|escape}">
  <input type="hidden" name="do" value="updatePassword">
  <article class="module width_half">
    <header>
      <h3>Passwort &auml;ndern</h3>
    </header>
    <div class="module_content">
      <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
      Hinweis: Nach erfolgter Passwort&auml;nderung wirst Du auf die Startseite umgeleitet
      </p>
      <fieldset>
        <label>Aktuelles Passwort</label>
        <input type="password" name="currentPassword" />
      </fieldset>
      <fieldset>
        <label>Neues Passwort</label>
        <input type="password" name="newPassword" />
      </fieldset>
      <fieldset>
        <label>Passwort best&auml;tigen</label>
        <input type="password" name="newPassword2" />
      </fieldset>
      <fieldset>
        <label>4 stellige PIN</label>
        <input type="password" name="authPin" size="4" maxlength="4" />
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Passwort &auml;ndern" class="alt_btn">
      </div>
    </footer>
  </article>
</form>
