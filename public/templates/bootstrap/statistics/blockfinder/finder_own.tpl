  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        Blocks found by own Workers
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th align="center">Rank</th>
              <th>Worker</th>
              <th align="center">Blocks</th>
              <th align="right" style="padding-right: 25px;">Coins Generated</th>
            </tr>
          </thead>
          <tbody>
{assign var=rank value=1}
{section block $BLOCKSSOLVEDBYWORKER}
            <tr class="{cycle values="odd,even"}">
              <td align="center">{$rank++}</td>
              <td>{$BLOCKSSOLVEDBYWORKER[block].finder|default:"unknown/deleted"|escape}</td>
              <td align="center">{$BLOCKSSOLVEDBYWORKER[block].solvedblocks}</td>
              <td align="right" style="padding-right: 25px;">{$BLOCKSSOLVEDBYWORKER[block].generatedcoins|number_format}</td>
            </tr>
{/section}
          </tbody>
        </table>
      </div>
    </div>
  </div>