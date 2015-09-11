<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info fa-fw"></i> {t}MPOS Version Information{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>{t}Component{/t}</th>
              <th>{t}Current{/t}</th>
              <th>{t}Installed{/t}</th>
              <th>{t}Online{/t}</th>
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
              <td><strong>{t}Config{/t}</strong></td>
              <td><font color="green">{$VERSION['CURRENT']['CONFIG']}</font></td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CONFIG'] == $VERSION['CURRENT']['CONFIG']}green{else}red{/if}">{$VERSION['INSTALLED']['CONFIG']}</font>
              </td>
              <td>
                <font color="{if $VERSION['INSTALLED']['CONFIG'] == $VERSION['ONLINE']['CONFIG']}green{else}red{/if}">{$VERSION['ONLINE']['CONFIG']}</font>
              </td>
            </tr>
            <tr>
              <td><strong>{t}Database{/t}</strong></td>
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
        <i class="fa fa-question fa-fw"></i> {t}MPOS Status{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th colspan="2">{t}Cronjobs{/t}</th>
              <th>{t}Wallet{/t}</th>
            </tr>
            <tr>
              <th><strong>{t}Errors{/t}</strong></th>
              <th><strong>{t}Disabled{/t}</strong></th>
              <th><strong>{t}Errors{/t}</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_ERROR == 0}{t}None - OK{/t}{else}{$CRON_ERROR}{/if}</a>
              </td>
              <td>
                <a href="{$smarty.server.SCRIPT_NAME}?page=admin&action=monitoring">{if $CRON_DISABLED == 0}{t}None - OK{/t}{else}{$CRON_DISABLED}{/if}</a>
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




