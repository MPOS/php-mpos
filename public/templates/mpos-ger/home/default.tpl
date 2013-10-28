{section name=news loop=$NEWS}
    <article class="module width_full">
      <header><h3>{$NEWS[news].header}, <font size=\"1px\">erstellt am {$NEWS[news].time|date_format:"%b %e, %Y at %H:%M"} von <b>{$NEWS[news].author}</b></font></h3></header>
      <div class="module_content">
        {$NEWS[news].content}
        <div class="clear"></div>
      </div>
    </article>
{/section}
