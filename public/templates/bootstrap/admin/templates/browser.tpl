  <div class="col-lg-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        Select Page
      </div>
      <div class="panel-content templates-tree" id="templates-tree">
        {include file="admin/templates/tree.tpl" files=$TEMPLATES prefix=""}
      </div>
      <link rel='stylesheet' type='text/css' href='{$PATH}/js/dynatree/skin/ui.dynatree.css'>
      <script type="text/javascript" src="{$PATH}/js/jquery.cookie.js"></script>
      <script type="text/javascript" src="{$PATH}/js/jquery-ui.custom.min.js"></script>
      <script type="text/javascript" src="{$PATH}/js/dynatree/jquery.dynatree.min.js"></script>
      <script>
        $(function() {
          $("#templates-tree").each(function() {
            $(this).find("li").each(function() {
              if($(this).find("li.dynatree-activated").length) {
                $(this).attr("data", "addClass:'dynatree-has-activated'");
              }
            });
          }).dynatree({
            minExpandLevel: 2,
            clickFolderMode: 2,
            selectMode: 1,
            persist: true,
            //To show the active template onLoad
            onPostInit: function(isReloading, isError) {
              this.reactivate();
            },
            onActivate: function(node) {
              if( node.tree.isUserEvent() && node.data.href )
                location.href = node.data.href;
            }
          });
        });
      </script>
      <style>
        .templates-tree .dynatree-container { border: none; }
        .templates-tree span.dynatree-folder a { font-weight: normal; }
        .templates-tree span.dynatree-active a,
        .templates-tree span.dynatree-has-activated a,
        .templates-tree span.dynatree-activated a { font-weight: bold; }
      </style>
      <div class="panel-footer">
        <h6><ul><li>Bold templates are activated</li></ul></h6>
      </div>
    </div>
  </div>
