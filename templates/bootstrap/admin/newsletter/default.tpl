<script src="{$PATH}/js/cleditor/jquery.cleditor.min.js"></script>
<link rel="stylesheet" href="{$PATH}/js/cleditor/jquery.cleditor.css">
<script type="text/javascript">
  $(document).ready(function () { $(".cleditor").cleditor(); });
</script>
<div class="row">
  <form class="col-lg-12" method="POST" action="{$smarty.server.SCRIPT_NAME}" role="form">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i> Write Newsletter
        <br>
        <font size="1px">Newsletters support the Markdown syntax</font>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" name="page" value="{$smarty.request.page|escape}">
            <input type="hidden" name="action" value="{$smarty.request.action|escape}">
            <input type="hidden" name="do" value="send">
            <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
            <div class="form-group">
              <label>Subject</label>
              <input class="form-control" size="30" type="text" name="data[subject]" value="{$smarty.request.data.subject|default:""}" required />
            </div>
            <div class="form-group">
              <label>Content</label>
              <textarea class="form-control cleditor" name="data[content]" rows="5" required>{$smarty.request.data.content|default:""}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <input type="submit" value="Send" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>
</div>
