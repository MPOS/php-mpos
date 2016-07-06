{section name=news loop=$NEWS}
<div class="row">
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          {$NEWS[news].header}
          <font size="1px">posted {$NEWS[news].time|date_format:$GLOBAL.config.date}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font>
        </div>
        <span class="tools">
          <i class="fa fa-bookmark"></i>
        </span>
      </div>
      <div class="widget-body">
        {$NEWS[news].content nofilter}
      </div>
    </div>
  </div>
</div>
{/section}
