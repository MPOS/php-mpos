  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          General Statistics
        </div>
        <span class="tools">
          <i class="fa fa-bar-chart"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <tbody>
              <tr>
                <td class="leftheader">Pool Hash Rate</td>
                <td>{$GLOBAL.hashrate} {$GLOBAL.hashunits.pool}</td>
              </tr>
              <tr>
                <td class="leftheader">Current Total Miners</td>
                <td>{$GLOBAL.workers}</td>
              </tr>
              <tr>
                <td class="leftheader">Current Block</td>
                <td><a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCK}" target="_new">{$CURRENTBLOCK}</a></td>
              </tr>
              <tr>
                <td class="leftheader">Current Difficulty</td>
                <td><a href="http://allchains.info/" target="_new">{$DIFFICULTY}</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="widget-footer">
        {if !$GLOBAL.website.api.disabled}<ul><li>These stats are also available in JSON format <a href="{$smarty.server.SCRIPT_NAME}?page=api&action=public" target="_api">HERE</a></li>{/if}
      </div>
    </div>
  </div>
</div>