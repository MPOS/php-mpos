{if $DISABLE_TRANSACTIONSUMMARY|default:"0" != 1}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-money fa-fw"></i> Total {$GLOBAL.config.currency} Earning Reports
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <i class="fa fa-credit-card fa-fw"></i> All Time
              </div>
              <div class="panel-body">
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

        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <i class="fa fa-clock-o fa-fw"></i> Sorted by Time
              </div>
              <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Credit</th>
                      <th>Debit AP</th>
                      <th>Debit MP</th>
                      <th>TXFee</th>
                      {if $GLOBAL.fees > 0}
                      <th>Fee</th>
                      {/if}
                      <th>Donation</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Last Hour</th>
                      <td>{$CREDIT.HourlyTrans|number_format:"8"}</td>
                      <td>{$DEBITAP.HourlyTrans|number_format:"8"}</td>
                      <td>{$DEBITMP.HourlyTrans|number_format:"8"}</td>
                      <td>{$TXFEE.HourlyTrans|number_format:"8"}</td>
                      {if $GLOBAL.fees|default:"0" > 0}
                      <td>{$FEE.HourlyTrans|number_format:"8"}</td>
                      {/if}
                      <td>{$DONATION.HourlyTrans|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td>Last Day</th>
                      <td>{$CREDIT.DailyTrans|number_format:"8"}</td>
                      <td>{$DEBITAP.DailyTrans|number_format:"8"}</td>
                      <td>{$DEBITMP.DailyTrans|number_format:"8"}</td>
                      <td>{$TXFEE.DailyTrans|number_format:"8"}</td>
                      {if $GLOBAL.fees|default:"0" > 0}
                      <td>{$FEE.DailyTrans|number_format:"8"}</td>
                      {/if}
                      <td>{$DONATION.DailyTrans|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td>Last Week</th>
                      <td>{$CREDIT.WeeklyTrans|number_format:"8"}</td>
                      <td>{$DEBITAP.WeeklyTrans|number_format:"8"}</td>
                      <td>{$DEBITMP.WeeklyTrans|number_format:"8"}</td>
                      <td>{$TXFEE.WeeklyTrans|number_format:"8"}</td>
                      {if $GLOBAL.fees|default:"0" > 0}
                      <td>{$FEE.WeeklyTrans|number_format:"8"}</td>
                      {/if}
                      <td>{$DONATION.WeeklyTrans|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td>Last Month</th>
                      <td>{$CREDIT.MonthlyTrans|number_format:"8"}</td>
                      <td>{$DEBITAP.MonthlyTrans|number_format:"8"}</td>
                      <td>{$DEBITMP.MonthlyTrans|number_format:"8"}</td>
                      <td>{$TXFEE.MonthlyTrans|number_format:"8"}</td>
                      {if $GLOBAL.fees|default:"0" > 0}
                      <td>{$FEE.MonthlyTrans|number_format:"8"}</td>
                      {/if}
                      <td>{$DONATION.MonthlyTrans|number_format:"8"}</td>
                    </tr>
                    <tr>
                      <td>Last Year</th>
                      <td>{$CREDIT.YearlyTrans|number_format:"8"}</td>
                      <td>{$DEBITAP.YearlyTrans|number_format:"8"}</td>
                      <td>{$DEBITMP.YearlyTrans|number_format:"8"}</td>
                      <td>{$TXFEE.YearlyTrans|number_format:"8"}</td>
                      {if $GLOBAL.fees|default:"0" > 0}
                      <td>{$FEE.YearlyTrans|number_format:"8"}</td>
                      {/if}
                      <td>{$DONATION.YearlyTrans|number_format:"8"}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{/if}






