  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        {t}General Statistics{/t}
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
          <tbody>
            <tr>
              <td class="leftheader">{t}Pool Hash Rate{/t}</td>
              <td>{$GLOBAL.hashrate} {t}{$GLOBAL.hashunits.pool}{/t}</td>
            </tr>
            <tr>
              <td class="leftheader">{t}Current Total Miners{/t}</td>
              <td>{$GLOBAL.workers}</td>
            </tr>
            <tr>
              <td class="leftheader">{t}Current Block{/t}</td>
              <td><a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCK}" target="_new">{$CURRENTBLOCK}</a></td>
            </tr>
            <tr>
              <td class="leftheader">{t}Current Difficulty{/t}</td>
              <td><a href="http://allchains.info/" target="_new">{$DIFFICULTY}</a></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        {if !$GLOBAL.website.api.disabled}<ul><li>{t}These stats are also available in JSON format{/t} <a href="{$smarty.server.SCRIPT_NAME}?page=api&action=public" target="_api">{t}HERE{/t}</a></li>{/if}
      </div>
    </div>
  </div>
</div>