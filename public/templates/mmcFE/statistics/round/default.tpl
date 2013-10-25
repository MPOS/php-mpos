{include file="global/block_header.tpl" BLOCK_HEADER="Round Statistics" BLOCK_STYLE="clear:none;"}

{if $GLOBAL.config.payout_system == 'pplns'}
   {include file="statistics/round/pplns_block_stats.tpl"}
   {include file="statistics/round/round_shares.tpl"}
   {include file="statistics/round/pplns_round_shares.tpl"}
   {include file="statistics/round/pplns_transactions.tpl"}
{else}
   {include file="statistics/round/block_stats.tpl"}
   {include file="statistics/round/round_shares.tpl"}
   {include file="statistics/round/round_transactions.tpl"}
{/if}

{include file="global/block_footer.tpl"}
