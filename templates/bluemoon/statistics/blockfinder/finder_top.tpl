  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Top 25 Blockfinder
        </div>
        <span class="tools">
          <i class="fa fa-user"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Blocks</th>
                <th>Coins Generated</th>
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
  </div>
