  <div class="col-lg-8">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Wallet Status
        </div>
        <span class="tools">
          <i class="fa fa-info"></i>
        </span>
      </div>
      <div class="widget-body">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <th>Version</th>
            <th>Protocol Version</th>
            <th>Wallet Version</th>
            <th>Peers</th>
            <th>Status</th>
            <th>Blocks</th>
            <th>Accounts</th>
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
