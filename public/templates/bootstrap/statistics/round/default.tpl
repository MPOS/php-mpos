{if $GLOBAL.config.payout_system == 'pplns'}
   {include file="statistics/round/pplns_block_stats.tpl"}
   {include file="statistics/round/pplns_transactions.tpl"}
	<div class="row">
   {include file="statistics/round/round_shares.tpl"}
   {include file="statistics/round/pplns_round_shares.tpl"}
	</div>
{else if $GLOBAL.config.payout_system == 'prop'}
   {include file="statistics/round/block_stats.tpl"}
   <div class="row">
   {include file="statistics/round/round_shares.tpl"}
   {include file="statistics/round/round_transactions.tpl"}
   </div>
{else}
   <div class="row">
   {include file="statistics/round/block_stats.tpl"}
   {include file="statistics/round/round_shares.tpl"}
   </div>
{/if}
