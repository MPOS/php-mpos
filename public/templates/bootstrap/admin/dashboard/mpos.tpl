<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info fa-fw"></i> MPOS Version Information
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Component</th>
              <th>Current</th>
              <th>Installed</th>
              <th>Online</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>MPOS</strong></td>
              <td><font color="green">{$VERSION['CURRENT']['CORE']}</font></td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CORE'] == $VERSION['CURRENT']['CORE']}green{else}red{/if}">{$VERSION['INSTALLED']['CORE']}</font>
              </td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CORE'] == $VERSION['ONLINE']['CORE']}green{else}red{/if}">{$VERSION['ONLINE']['CORE']}</font>
              </td>
            </tr>
            <tr>
              <td><strong>Config</strong></td>
              <td><font color="green">{$VERSION['CURRENT']['CONFIG']}</font></td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CONFIG'] == $VERSION['CURRENT']['CONFIG']}green{else}red{/if}">{$VERSION['INSTALLED']['CONFIG']}</font>
              </td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CONFIG'] == $VERSION['ONLINE']['CONFIG']}green{else}red{/if}">{$VERSION['ONLINE']['CONFIG']}</font>
              </td>
            </tr>
            <tr>
              <td><strong>Database</strong></td>
              <td><font color="green">{$VERSION['CURRENT']['DB']}</font></td>
              <td>
                <font color="{if $VERSION['INSTALLED']['DB'] == $VERSION['CURRENT']['DB']}green{else}red{/if}">{$VERSION['INSTALLED']['DB']}</font>
              </td>
              <td>
                <font color="{if $VERSION['INSTALLED']['DB'] == $VERSION['ONLINE']['DB']}green{else}red{/if}">{$VERSION['ONLINE']['DB']}</font>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-question fa-fw"></i> MPOS Status
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th colspan="2">Cronjobs</th>
              <th>Wallet</th>
            </tr>
            <tr>
              <th><strong>Errors</strong></th>
              <th><strong>Disabled</strong></th>
              <th><strong>Errors</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_ERROR == 0}None - OK{else}{$CRON_ERROR}{/if}</a>
              </td>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_DISABLED == 0}None - OK{else}{$CRON_DISABLED}{/if}</a>
              </td>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=wallet">{$WALLET_ERROR|default:"None - OK"}</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




