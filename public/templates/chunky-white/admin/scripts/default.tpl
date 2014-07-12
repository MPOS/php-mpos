{if $OUTPUT}
<article class="module col-md-12">
  <header><h3>Output</h3></header>
  <div>
    {$OUTPUT}
  </div>
</article>
{/if}
<article class="module col-md-6">
  <header><h3>Scripts</h3></header>
  <h4>Update Prices from Spreadsheet</h4>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="prices">
    <footer>
      <div class="submit_link">
        <input type="submit" value="Submit" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
<article class="module col-md-6">
  <h4>Payout Mode (currently in {$SCRIPT_MODE})</h4>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="change_mode">
    <div class="module_content">
      <fieldset>
        <label>Mode</label>
        <select name="data[mode]" required />
          <option value="test">Test</option>
          <option value="payout_mode">Send To Exchange</option>
          <option value="process">Payout</option>
        </select>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Submit" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
<article class="module col-md-6">
  <h4>Payouts</h4>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="payout">
    <div class="module_content">
      <fieldset>
        <label>Coin</label>
        <select name="data[coin]" required />
          <option>LGC</option>
          <option>KARM</option>
          <option>TRC</option>
          <option>RDD</option>
        </select>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Submit" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
<article class="module col-md-6">
  <h4>Change Multiport Coins</h4>
  <form method="POST" action="{$smarty.server.PHP_SELF}">
    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
    <input type="hidden" name="action" value="{$smarty.request.action|escape}">
    <input type="hidden" name="do" value="multiport">
    <div class="module_content">
      <fieldset>
        <label>Coins</label>
        <select name="data[coin]" required />
          <option value="karm,lgc,trc">KARM - LGC - TRC</option>
          <option value="rdd,lgc,trc">RDD - LGC - TRC</option>
          <option value="pot,lgc,trc">POT - LGC - TRC</option>
        </select>
      </fieldset>
    </div>
    <footer>
      <div class="submit_link">
        <input type="submit" value="Submit" class="btn btn-primary">
      </div>
    </footer>
  </form>
</article>
