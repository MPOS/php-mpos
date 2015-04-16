<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-connectdevelop fa-fw"></i> Last {$TRANSACTIONS|count} transactions
      </div>
      <div class="panel-body no-padding">
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th>Account</th>
            <th class="text-center">Address</th>
            <th class="text-center">Category</th>
            <th class="text-right">Amount</th>
            <th class="text-right">Confirmations</th>
            <th class="text-right">Time</th>
          </tr>
          </thead>
          <tbody>
{foreach key=KEY item=ARRAY from=$TRANSACTIONS}
          <tr>
            <td>{$ARRAY['account']|default:"Default"}</td>
            <td class="text-center">{$ARRAY['address']}</td>
            <td class="text-center">{$ARRAY['category']|capitalize}</td>
            <td class="text-right">{$ARRAY['amount']|number_format:"$PRECISION"}</td>
            <td class="text-right">{$ARRAY['confirmations']}</td>
            <td class="text-right">{$ARRAY['time']|date_format:$GLOBAL.config.date}
          </tr>
{/foreach}
          </tbody>
        </table>
      </div>
    </div>
