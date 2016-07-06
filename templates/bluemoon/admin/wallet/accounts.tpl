  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header">
          <div class="title">
            Wallet Accounts
          </div>
          <span class="tools">
            <i class="fa fa-users"></i>
          </span>
        </div>
        <div class="widget-body ">
          <div class="panel-group">
            {foreach key=NAME item=VALUE from=$ACCOUNTS}
            <div class="widget">
              <div class="widget-header">
                <div class="title">
                  Account: {$NAME|default:"Default"}
                </div>
                <span class="tools">
                  <i class="fa fa-user"></i>
                </span>
              </div>
              <div class="panel-body">
                <div class="col-lg-4">
                  <div class="widget">
                    <div class="widget-header">
                      <div class="title">
                        Balance Info
                      </div>
                      <span class="tools">
                        <i class="fa fa-money"></i>
                      </span>
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
                  <div class="widget">
                    <div class="widget-header">
                      <div class="title">
                        Addresses assigned to Account {$ACCOUNT|default:"Default"}
                      </div>
                      <span class="tools">
                        <i class="fa fa-book"></i>
                      </span>
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
            </div>
            {/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>
