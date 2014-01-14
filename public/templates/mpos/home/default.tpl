{section name=news loop=$NEWS}
    <article class="module width_full">
      <header><h3>{$NEWS[news].header}, <font size=\"1px\">posted {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font></h3></header>
      <div class="module_content">
        {$NEWS[news].content nofilter}
        <div class="clear"></div>
      </div>
    </article>
{/section}
