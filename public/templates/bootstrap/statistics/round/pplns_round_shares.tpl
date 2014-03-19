  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-refresh fa-fw"></i> PPLNS Round Shares
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover {if $PPLNSROUNDSHARES}datatable{/if}">
            <thead>
              <tr>
                <th>Rank</th>
                <th >User Name</th>
                <th >Valid</th>
                <th >Invalid</th>
                <th>Invalid %</th>
              </tr>
            </thead>
            <tbody>
{assign var=rank value=1}
{section contrib $PPLNSROUNDSHARES}
              <tr{if $GLOBAL.userdata.username|default:"" == $PPLNSROUNDSHARES[contrib].username} style="background-color:#99EB99;"{else}{/if}>
                <td>{$rank++}</td>
                <td>{if $PPLNSROUNDSHARES[contrib].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$PPLNSROUNDSHARES[contrib].username|default:"unknown"|escape}{/if}</td>
                <td>{$PPLNSROUNDSHARES[contrib].pplns_valid|number_format}</td>
                <td>{$PPLNSROUNDSHARES[contrib].pplns_invalid|number_format}</td>
                <td>{if $PPLNSROUNDSHARES[contrib].pplns_invalid > 0 && $PPLNSROUNDSHARES[contrib].pplns_valid > 0}{($PPLNSROUNDSHARES[contrib].pplns_invalid / $PPLNSROUNDSHARES[contrib].pplns_valid * 100)|number_format:"2"|default:"0"}{else}0.00{/if}</td>
              </tr>
{/section}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
