  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <i class="fa fa-users fa-fw"></i> Wallet Accounts
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
