{if $DISABLE_TRANSACTIONSUMMARY|default:"0" != 1}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-money fa-fw"></i> Total Earning Stats
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
              {foreach $SUMMARY as $type=>$total}
                <th>{$type}</th>
              {/foreach}
              </tr>
            </thead>
            <tbody>
              <tr>
              {foreach $SUMMARY as $type=>$total}
                <td class="right">{$total|number_format:"8"}</td>
              {/foreach}
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
{/if}