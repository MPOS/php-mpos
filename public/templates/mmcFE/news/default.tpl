{section name=news loop=$NEWS}
  {include file="global/block_header.tpl" BLOCK_HEADER="{$NEWS[news].header}  posted {$NEWS[news].time} by {$NEWS[news].author}"}
  {$NEWS[news].content}
  {include file="global/block_footer.tpl"}
{/section}
