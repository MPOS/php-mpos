{section name=news loop=$NEWS}
  {include file="global/block_header.tpl" BLOCK_HEADER="{$NEWS[news].header}, <font size=\"1px\">posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"} by <b>{$NEWS[news].author}</b></font>"}
  {$NEWS[news].content}
  {include file="global/block_footer.tpl"}
{/section}
