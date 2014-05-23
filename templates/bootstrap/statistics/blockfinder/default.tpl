<div class="row">
{include file="statistics/blockfinder/finder_top.tpl"}
{if $smarty.session.AUTHENTICATED|default}
{include file="statistics/blockfinder/finder_own.tpl" SHORT=true}
{/if}
</div>