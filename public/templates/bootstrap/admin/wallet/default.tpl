<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        Balance Summary
      </div>
      <div class="panel-body">
        <table class="table">
          <tr>
            <td align="left">Wallet Balance</td>
            <td align="left">{$BALANCE|number_format:"8"}</td>
          </tr>
          <tr>
            <td align="left">Locked for users</td>
            <td align="left">{$LOCKED|number_format:"8"}</td>
          </tr>
          <tr>
            <td align="left">Unconfirmed</td>
            <td align="left">{$UNCONFIRMED|number_format:"8"}</td>
          </tr>
          <tr>
            <td align="left">Liquid Assets</td>
            <td align="left">{($BALANCE - $LOCKED - $UNCONFIRMED + $NEWMINT|default:"0")|number_format:"8"}</td>
          </tr>
{if $NEWMINT >= 0}
          <tr>
            <td align="left">PoS New Mint</td>
            <td align="left">{$NEWMINT|number_format:"8"}</td>
          </tr>
{/if}
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        Wallet Status
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <th align="center">Version</th>
            <th align="center">Protocol Version</th>
            <th align="center">Wallet Version</th>
            <th align="center">Connections</th>
            <th align="center">Errors</th>
          </thead>
          <tbody>
            <tr>
              <td align="center">{$COININFO.version|default:""}</td>
              <td align="center">{$COININFO.protocolversion|default:""}</td>
              <td align="center">{$COININFO.walletversion|default:""}</td>
              <td align="center">{$COININFO.connections|default:""}</td>
              <td align="center"><font color="{if $COININFO.errors}red{else}green{/if}">{$COININFO.errors|default:"OK"}</font></td>
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
          Number of Accounts in Wallet: {$ADDRESSCOUNT|default:"0"}
        </div>
        
        <div class="panel-body">
          <div class="panel-group">

{foreach key=NAME item=VALUE from=$ACCOUNTS}
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  Account: {$NAME|default:"Default"}
                </h4>
                <div class="col-lg-4">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        Balance Info
                      </h4>
                    </div>
                    <div class="panel-body">
                      <table class="table">
                        <tr>
                          <td align="left">Balance</td>
                          <td align="left">{$VALUE|number_format:"8"}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>

{foreach key=ACCOUNT item=ADDRESS from=$ACCOUNTADDRESSES}
{if $ACCOUNT == $NAME}

                <div class="col-lg-8">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        Addresses assigned to Account {$ACCOUNT|default:"Default"}
                      </h4>
                    </div>
                    <div class="panel-body">
                      <table class="table">
                        <tbody>
{foreach from=$ACCOUNTADDRESSES[$ACCOUNT] key=ACCOUNT1 item=ADDRESS1}
{if $ADDRESS1@iteration is even by 1}
                            <td align="left" style="padding-right: 25px;">{$ADDRESS1}</td>
                          </tr>
{else}
                          <tr>
                            <td align="left" style="padding-right: 25px;">{$ADDRESS1}</td>
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
            </div>
{/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{/if}
