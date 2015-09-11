<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-connectdevelop fa-fw"></i> {t 1=$TRANSACTIONS|count}Last %1 transactions{/t}
      </div>
      <div class="panel-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
              <th class="text-center">{t}Account{/t}</th>
              <th class="text-center">{t}Address{/t}</th>
              <th class="text-center">{t}Category{/t}</th>
              <th class="text-right">{t}Amount{/t}</th>
              <th class="text-right">{t}Confirmations{/t}</th>
              <th class="text-center">{t}Transaction ID{/t}</th>
              <th class="text-right">{t}Time{/t}</th>
            </tr>
            </thead>
            <tbody>
{foreach key=KEY item=ARRAY from=$TRANSACTIONS}
            <tr>
              <td class="text-center">{$ARRAY['account']|default:"Default"}</td>
              <td class="text-center">{$ARRAY['address']}</td>
              <td class="text-center">{$ARRAY['category']|capitalize}</td>
              <td class="text-right">{$ARRAY['amount']|number_format:"$PRECISION"}</td>
              <td class="text-right">{$ARRAY['confirmations']}</td>
              <td class="text-center">
                {if !$GLOBAL.website.transactionexplorer.disabled}
                <a href="{$GLOBAL.website.transactionexplorer.url}{$ARRAY['txid']}">{$ARRAY['txid']|truncate:20:"...":false:true}</a>
                {else}
                {$ARRAY['txid']|truncate:20:"...":false:true}
                {/if}
              </td>
              <td class="text-right">{$ARRAY['time']|date_format:$GLOBAL.config.date}
            </tr>
{/foreach}
            </tbody>
          </table>
        </div>
      </div>
    </div>
