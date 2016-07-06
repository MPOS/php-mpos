<script src="{$GLOBALASSETS}/js/plugins/cleditor/jquery.cleditor.min.js"></script>
<link rel="stylesheet" href="{$GLOBALASSETS}/js/plugins/cleditor/jquery.cleditor.css">
<script type="text/javascript">
  $(document).ready(function () { $(".cleditor").cleditor(); });
</script>
<div class="row">
  <form class="col-lg-12" method="POST" action="{$smarty.server.SCRIPT_NAME}" role="form">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Write News
          <font size="1px">News posts support the Markdown syntax</font>
        </div>
        <span class="tools">
          <i class="fa fa-edit"></i>
        </span>
      </div>
      <div class="widget-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" name="page" value="{$smarty.request.page|escape}">
            <input type="hidden" name="action" value="{$smarty.request.action|escape}">
            <input type="hidden" name="do" value="add">
            <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
            <div class="form-group">
              <label>Active</label>
              <input type="checkbox" class="switch" data-size="mini" name="data[active]" value="1" checked>
            </div>
            <div class="form-group">
              <label>Header</label>
              <input class="form-control" size="30" type="text" name="data[header]" required />
            </div>
            <div class="form-group">
              <label>Content</label>
              <textarea class="form-control cleditor" name="data[content]" rows="5" required></textarea>
            </div>
          </div>
        </div>
        <input type="submit" value="Add" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>
</div>

<div class="row">
{nocache}
{section name=news loop=$NEWS}
  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          {$NEWS[news].header}
          <font size="1px">posted {$NEWS[news].time|date_format:$GLOBAL.config.date}{if $HIDEAUTHOR|default:"0" == 0} by <b>{$NEWS[news].author}</b>{/if}</font>
        </div>
        <span class="tools">
          <i class="fa fa-info"></i>
        </span>
      </div>
      <div class="widget-body">
        {$NEWS[news].content nofilter}
        <div style="text-align:right">
          <a href='{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action=news_edit&id={$NEWS[news].id}'><i class="fa fa-wrench fa-fw"></i></a>&nbsp;
          <a href='{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&do=delete&id={$NEWS[news].id}&ctoken={$CTOKEN|escape|default:""}'><i class="fa fa-trash-o fa-fw"></i></a>
        </div>
      </div>
    </div>
  </div>
{/section}
{/nocache}
</div>
