  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Last Found Blocks
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th>Block</th>
              <th>Finder</th>
              <th>Time</th>
              <th>Actual Shares</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
            <tr class="{cycle values="odd,even"}">
              {if ! $GLOBAL.website.blockexplorer.disabled}
              <td align="center"><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKSFOUND[block].blockhash}" target="_new">{$BLOCKSFOUND[block].height}</a></td>
              {else}
              <td align="center">{$BLOCKSFOUND[block].height}</td>
              {/if}
              <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
              <td align="left">{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
              <td align="left">{$BLOCKSFOUND[block].shares|number_format}</td>
            </tr>
{/section}
          </tbody>
        </table>
      </div>
      <!-- /.panel -->
{if $GLOBAL.config.payout_system != 'pps'}
      <div class="panel-footer">
        <ul>
          <li>Note: Round Earnings are not credited until <font color="orange">{$GLOBAL.confirmations}</font> confirms.</font></li>
        </ul>
      </div>
{/if}
    </div>
    <!-- /.col-lg-12 -->
  </div>
