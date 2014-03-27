    <form class="col-lg-9" method="POST" action="{$smarty.server.SCRIPT_NAME}">
      <input type="hidden" name="page" value="{$smarty.request.page}">
      <input type="hidden" name="action" value="{$smarty.request.action}">
      <input type="hidden" name="template" value="{$CURRENT_TEMPLATE}">
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
      <div class="panel panel-info">
        <div class="panel-heading">
          <i class="fa fa-pencil fa-fw"></i> Edit template '{$CURRENT_TEMPLATE}'
        </div>
        <div class="panel-body no-padding">
          <table class="table table-striped table-bordered table-hover">
            <tr>
              <td><label>Active</label></td>
              <td>
                <input type="hidden" name="active" value="0" />
                <input type="checkbox" data-size="small" class="switch" name="active" value="1" id="active" {nocache}{if $DATABASE_TEMPLATE.active}checked{/if}{/nocache} />
              </td>
            </tr>
            <tr>
              <td><label>Content</label></td>
              <td>
                <textarea name="content" rows="15" type="text" class="form-control" required>{nocache}{$DATABASE_TEMPLATE.content nofilter}{/nocache}</textarea>
              </td>
            </tr>
            <tr>
              <td><label>Original Template Content</label></td>
              <td>
                <textarea readonly rows="15" type="text" class="form-control">{nocache}{$ORIGINAL_TEMPLATE nofilter}{/nocache}</textarea>
              </td>
            </tr>
          </table>
        </div>
        <div class="panel-footer">
          <input type="submit" value="Save" class="btn btn-success btn-sm">
        </div>
      </div>
    </form>
