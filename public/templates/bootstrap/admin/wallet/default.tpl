<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-money fa-fw"></i> Balance Summary
      </div>
      <div class="panel-body no-padding">
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

  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info fa-fw"></i> Wallet Status
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <th>Version</th>
            <th>Protocol Version</th>
            <th>Wallet Version</th>
            <th>Connections</th>
            <th>Errors</th>
          </thead>
          <tbody>
            <tr>
              <td>{$COININFO.version|default:""}</td>
              <td>{$COININFO.protocolversion|default:""}</td>
              <td>{$COININFO.walletversion|default:""}</td>
              <td>{$COININFO.connections|default:""}</td>
              <td><font color="{if $COININFO.errors}red{else}green{/if}">{$COININFO.errors|default:"OK"}</font></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

{if $ADDRESSCOUNT > 1}

  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <i class="fa fa-users fa-fw"></i> Number of Accounts in Wallet: {$ADDRESSCOUNT|default:"0"}
        </div>
        <div class="panel-body ">
          <div class="panel-group">
{foreach key=NAME item=VALUE from=$ACCOUNTS}
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-user fa-fw"></i> Account: {$NAME|default:"Default"}
              </div>
              <div class="panel-body">
                <div class="col-lg-4">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <i class="fa fa-money fa-fw"></i> Balance Info
                    </div>
                    <div class="table-responsive panel-body no-padding">
                      <table class="table table-striped table-bordered table-hover">
                        <tr>
                          <td class="col-lg-4">Balance</td>
                          <td class="col-lg-12">{$VALUE|number_format:"8"}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

{foreach key=ACCOUNT item=ADDRESS from=$ACCOUNTADDRESSES}
{if $ACCOUNT == $NAME}

                <div class="col-lg-8">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <i class="fa fa-book fa-fw"></i> Addresses assigned to Account {$ACCOUNT|default:"Default"}
                    </div>
                    <div class="table-responsive panel-body no-padding">
                      <table class="table table-striped table-bordered table-hover">
                        <tbody>
{foreach from=$ACCOUNTADDRESSES[$ACCOUNT] key=ACCOUNT1 item=ADDRESS1}
{if $ADDRESS1@iteration is even by 1}
                            <td>{$ADDRESS1}</td>
                          </tr>
{else}
                          <tr>
                            <td>{$ADDRESS1}</td>
{/if}
{/foreach}
                        <tbody>
                      </table>
{/if}
{/foreach}
                    </div>
                  </div>
                </div>
              </div>
              <br>
            </div>
{/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{/if}
