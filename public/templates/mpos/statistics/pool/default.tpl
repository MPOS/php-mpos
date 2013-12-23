{include file="statistics/pool/contributors_shares.tpl"}

{include file="statistics/pool/contributors_hashrate.tpl"}

{include file="statistics/pool/general_stats.tpl"}

{include file="statistics/blocks/small_table.tpl" ALIGN="right" SHORT=true}

{if !$GLOBAL.website.api.disabled && !$GLOBAL.config.disable_navbar && !$GLOBAL.config.disable_navbar_api}
{include file="statistics/js.tpl"}
{/if}
