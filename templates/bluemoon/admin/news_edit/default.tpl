<script src="{$PATH}/js/cleditor/jquery.cleditor.min.js"></script>
<link rel="stylesheet" href="{$PATH}/js/cleditor/jquery.cleditor.css">
<script type="text/javascript">
  $(document).ready(function () { $(".cleditor").cleditor(); });
</script>


<div class="row">
  <form class="col-lg-12" method="POST" action="{$smarty.server.SCRIPT_NAME}" role="form">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-edit fa-fw"></i> Edit news entry #{$NEWS.id}
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
              <input type="hidden" name="page" value="{$smarty.request.page|escape}">
              <input type="hidden" name="action" value="{$smarty.request.action|escape}">
              <input type="hidden" name="id" value="{$NEWS.id}">
              <input type="hidden" name="do" value="save">
              <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
              
              <div class="form-group">
                <th>Active</th>
                <td>
                  <input type="hidden" name="active" value="0" />
                  <input type="checkbox" class="switch" data-size="mini" name="active" value="1" id="active" {nocache}{if $NEWS.active}checked{/if}{/nocache}>
                </td>
              </div>
              <div class="form-group">
                  <th>Header</th>
                  <td><input class="form-control" name="header" type="text" size="30" value="{nocache}{$NEWS.header}{/nocache}" required /></td>
              </div>
              <div class="form-group">
                  <th>Content</th>
                  <td><textarea class="cleditor form-control" name="content" rows="15" cols="150" type="text" required>{nocache}{$NEWS.content nofilter}{/nocache}</textarea></td>
              </div>
          </div>
        </div>  
      </div>
      <div class="panel-footer">
        <input type="submit" value="Save" class="btn btn-success btn-sm">
      </div>
    </div>
  </form>
</div>
