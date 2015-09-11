  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-money fa-fw"></i> {t}Balance Summary{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <td>{t}Wallet Balance{/t}</td>
            <td>{$BALANCE|number_format:"8"}</td>
          </tr>
          <tr>
            <td>{t}Locked for users{/t}</td>
            <td>{$LOCKED|number_format:"8"}</td>
          </tr>
          <tr>
            <td>{t}Unconfirmed{/t}</td>
            <td>{$UNCONFIRMED|number_format:"8"}</td>
          </tr>
{if $NEWMINT >= 0}
          <tr>
            <td>{t}Liquid Assets{/t}</td>
            {if $GLOBAL.config.getbalancewithunconfirmed}
            <td>{($BALANCE - $LOCKED - $UNCONFIRMED + $NEWMINT|default:"0")|number_format:"8"}</td>
            {else}
            <td>{($BALANCE - $LOCKED + $NEWMINT|default:"0")|number_format:"8"}</td>
            {/if}
          </tr>
          <tr>
            <td>{t}PoS New Mint{/t}</td>
            <td>{$NEWMINT|number_format:"8"}</td>
          </tr>
{else}
          <tr>
            <td>{t}Liquid Assets{/t}</td>
            {if $GLOBAL.config.getbalancewithunconfirmed}
            <td>{($BALANCE - $LOCKED - $UNCONFIRMED)|number_format:"8"}</td>
            {else}
            <td>{($BALANCE - $LOCKED)|number_format:"8"}</td>
            {/if}
          </tr>
{/if}
        </table>
      </div>
    </div>
  </div>
