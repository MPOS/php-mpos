  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          {if $LABELSCOMMAND}
            <i class="fa fa-users fa-fw"></i> Wallet Labels
          {else}
            <i class="fa fa-users fa-fw"></i> Wallet Accounts
          {/if}
        </div>
        <div class="panel-body ">
          <div class="panel-group">
{foreach key=NAME item=VALUE from=$ACCOUNTS}
            <div class="panel panel-default">
              <div class="panel-heading">
                {if $LABELSCOMMAND}
                  <i class="fa fa-user fa-fw"></i> Label: {$VALUE|default:"Default"}
                {else}
                  <i class="fa fa-user fa-fw"></i> Account: {$NAME|default:"Default"}
                {/if}
              </div>
              <div class="panel-body">
                {if (not ($LABELSCOMMAND))}
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
                {/if}

{foreach key=ACCOUNT item=ADDRESS from=$ACCOUNTADDRESSES}
{if $ACCOUNT == $NAME}
              {if $LABELSCOMMAND}
                <div class="col-lg-12">
              {else}
                <div class="col-lg-8">
              {/if}
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      {if $LABELSCOMMAND}
                        <i class="fa fa-book fa-fw"></i> Addresses assigned to Label {$VALUE|default:"Default"}
                      {else}
                        <i class="fa fa-book fa-fw"></i> Addresses assigned to Account {$ACCOUNT|default:"Default"}
                      {/if}
                    </div>
                    <div class="table-responsive panel-body no-padding">
                      <table class="table table-striped table-bordered table-hover">
                        <tbody>
{foreach from=$ACCOUNTADDRESSES[$ACCOUNT] key=ACCOUNT1 item=ADDRESS1}
{if not $LABELSCOMMAND}
  {if $ADDRESS1@iteration is even by 1}
                              <td>{$ADDRESS1}</td>
                            </tr>
  {else}
                            <tr>
                              <td>{$ADDRESS1}</td>
  {/if}
{else}
  {foreach from=$ACCOUNT1 key=ACCOUNT2 item=ADDRESS2}
    <tr>
      <td>{$ADDRESS2}</td>
    </tr>
  {/foreach}
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
