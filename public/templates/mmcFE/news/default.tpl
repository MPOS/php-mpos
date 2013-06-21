{section name=news loop=$NEWS}
  {include file="global/block_header.tpl" BLOCK_HEADER="{$NEWS[news].header}"}
  {$NEWS[news].content}
  {include file="global/block_footer.tpl"}
{/section}
