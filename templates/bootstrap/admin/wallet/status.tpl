  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-info fa-fw"></i> {t}Wallet Status{/t}
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <th>{t}Version{/t}</th>
            <th>{t}Protocol Version{/t}</th>
            <th>{t}Wallet Version{/t}</th>
            <th>{t}Peers{/t}</th>
            <th>{t}Status{/t}</th>
            <th>{t}Blocks{/t}</th>
            <th>{t}Accounts{/t}</th>
          </thead>
          <tbody>
            <tr>
              <td>{$COININFO.version|default:""}</td>
              <td>{$COININFO.protocolversion|default:""}</td>
              <td>{$COININFO.walletversion|default:""}</td>
              <td>{$COININFO.connections|default:""}</td>
              <td><font color="{if $COININFO.errors}red{else}green{/if}">{$COININFO.errors|default:"OK"}</font></td>
              <td>{$COININFO.blocks|default:"0"}</td>
              <td>{$ADDRESSCOUNT}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
