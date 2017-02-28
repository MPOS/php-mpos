    <div class="row">
      <div class="col-lg-12">
        <div class="widget">
          <div class="widget-header">
            <div class="title">
              latest News
            </div>
            <span class="tools">
              <i class="fa fa-info-circle"></i>
            </span>
          </div>
          <div class="widget-body">
            <div class="panel-group" id="accordion">
              {section name=news loop=$NEWS}
              <div class="panel panel-default">
                <div class="widget-header">
                  <div class="title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{$smarty.section.news.index}">{$NEWS[news].header}</a>
                    <font size="1px">posted {$NEWS[news].time|date_format:$GLOBAL.config.date}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font>
                  </div>
                  <span class="tools">
                    <i class="fa fa-info"></i>
                  </span>
                </div>
                <div id="collapse{$smarty.section.news.index}" class="panel-collapse collapse {if $smarty.section.news.index == 0}in{/if}">
                  <div class="panel-body">
                    {$NEWS[news].content nofilter}
                  </div>
                </div>
              </div>
              {/section}
            </div>
          </div>
        </div>
      </div>
    </div>
