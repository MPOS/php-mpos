  <div class="col-lg-4">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Balance Summary
        </div>
        <span class="tools">
          <i class="fa fa-money"></i>
        </span>
      </div>
      <div class="widget-body">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <td>Wallet Balance</td>
            <td>{$BALANCE|number_format:"8"}</td>
          </tr>
          <tr>
            <td>Locked for users</td>
            <td>{$LOCKED|number_format:"8"}</td>
          </tr>
          <tr>
            <td>Unconfirmed</td>
            <td>{$UNCONFIRMED|number_format:"8"}</td>
          </tr>
          {if $NEWMINT >= 0}
          <tr>
            <td>Liquid Assets</td>
            {if $GLOBAL.config.getbalancewithunconfirmed}
            <td>{($BALANCE - $LOCKED - $UNCONFIRMED + $NEWMINT|default:"0")|number_format:"8"}</td>
            {else}
            <td>{($BALANCE - $LOCKED + $NEWMINT|default:"0")|number_format:"8"}</td>
            {/if}
          </tr>
          <tr>
            <td>PoS New Mint</td>
            <td>{$NEWMINT|number_format:"8"}</td>
          </tr>
          {else}
          <tr>
            <td>Liquid Assets</td>
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
