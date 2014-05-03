  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-th fa-fw"></i> Last Found Blocks
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>Block</th>
              <th>Finder</th>
              <th>Time</th>
              <th class="text-right">Actual Shares</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
            <tr>
              {if ! $GLOBAL.website.blockexplorer.disabled}
              <td><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKSFOUND[block].blockhash}" target="_new">{$BLOCKSFOUND[block].height}</a></td>
              {else}
              <td>{$BLOCKSFOUND[block].height}</td>
              {/if}
              <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
              <td>{$BLOCKSFOUND[block].time|date_format:$GLOBAL.config.date}</td>
              <td class="text-right">{$BLOCKSFOUND[block].shares|number_format}</td>
            </tr>
{/section}
          </tbody>
        </table>
      </div>
{if $GLOBAL.config.payout_system != 'pps'}
      <div class="panel-footer">
          <h6>Note: Round Earnings are not credited until <font class="confirmations">{$GLOBAL.confirmations}</font> confirms.</h6>
      </div>
{/if}
    </div>
  </div>
