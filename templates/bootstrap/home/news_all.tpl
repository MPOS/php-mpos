{section name=news loop=$NEWS}
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-bookmark"></i> {$NEWS[news].header}
        <br />
        <font size="1px">posted {$NEWS[news].time|date_format:$GLOBAL.config.date}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font>
      </div>
      <div class="panel-body">
        {$NEWS[news].content nofilter}
      </div>
    </div>
  </div>
</div>
{/section}
