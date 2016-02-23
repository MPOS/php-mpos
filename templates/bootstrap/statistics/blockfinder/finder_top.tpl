  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-user fa-fw"></i> {t}Top 25 Blockfinder{/t}
      </div>
      <div class="panel-body no-padding table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>{t}Rank{/t}</th>
              <th>{t}Username{/t}</th>
              <th>{t}Blocks{/t}</th>
              <th>{t}Coins Generated{/t}</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{section block $BLOCKSSOLVEDBYACCOUNT}
      <tr>
              <td>{$rank++}</td>
              <td>{if $BLOCKSSOLVEDBYACCOUNT[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$BLOCKSSOLVEDBYACCOUNT[block].finder|default:"unknown"|escape}{/if}</td>
              <td>{$BLOCKSSOLVEDBYACCOUNT[block].solvedblocks}</td>
              <td>{$BLOCKSSOLVEDBYACCOUNT[block].generatedcoins|number_format}</td>
            </tr>
{/section}
          </tbody>
        </table>
      </div>
    </div>
  </div>
