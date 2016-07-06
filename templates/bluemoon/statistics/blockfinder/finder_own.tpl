  <div class="col-lg-6">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Blocks found by own Workers
        </div>
        <span class="tools">
          <i class="fa fa-desktop"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Rank</th>
                <th>Worker</th>
                <th>Blocks</th>
                <th>Coins Generated</th>
              </tr>
            </thead>
            <tbody>
              {assign var=rank value=1}
              {section block $BLOCKSSOLVEDBYWORKER}
              <tr>
                <td>{$rank++}</td>
                <td>{$BLOCKSSOLVEDBYWORKER[block].finder|default:"unknown/deleted"|escape}</td>
                <td>{$BLOCKSSOLVEDBYWORKER[block].solvedblocks}</td>
                <td>{$BLOCKSSOLVEDBYWORKER[block].generatedcoins|number_format}</td>
              </tr>
              {/section}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>