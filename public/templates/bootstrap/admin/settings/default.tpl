<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-gear fa-fw"></i> Settings
      </div>           
      <form method="POST" role="form">
        <input type="hidden" name="page" value="{$smarty.request.page|escape}" />
        <input type="hidden" name="action" value="{$smarty.request.action|escape}" />
        <input type="hidden" name="do" value="save" />
        <input type="hidden" name="ctoken" value="{$CTOKEN|escape|default:""}" />
        <div class="panel-body">
          <ul class="nav nav-pills">
            {foreach item=TAB from=array_keys($SETTINGS)}
            <li {if $TAB == 'website'}class="active"{/if}><a href="#{$TAB}" data-toggle="tab">{$TAB|capitalize}</a></li>
            {/foreach}
          </ul>
          <div class="tab-content">
            {foreach item=TAB from=array_keys($SETTINGS)}
            <div class="tab-pane fade in {if $TAB == 'website'}active{/if}" id="{$TAB}">
              <br />
              {section name=setting loop=$SETTINGS.$TAB}
              <div class="form-group">
              <label>{$SETTINGS.$TAB[setting].display}</label>
              {if $SETTINGS.$TAB[setting].tooltip|default}<span style="font-size: 10px;">{$SETTINGS.$TAB[setting].tooltip}</span>{/if}
              {if $SETTINGS.$TAB[setting].type == 'select'}
                {html_options class="form-control select-mini" name="data[{$SETTINGS.$TAB[setting].name}]" options=$SETTINGS.$TAB[setting].options selected=$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}
              {else if $SETTINGS.$TAB[setting].type == 'text'}
                <input class="form-control" type="text" size="{$SETTINGS.$TAB[setting].size|default:"1"}" name="data[{$SETTINGS.$TAB[setting].name}]" value="{$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default|escape:"html"}" />
              {else if $SETTINGS.$TAB[setting].type == 'textarea'}
                <textarea class="form-control" name="data[{$SETTINGS.$TAB[setting].name}]" cols="{$SETTINGS.$TAB[setting].size|default:"1"}" rows="{$SETTINGS.$TAB[setting].height|default:"1"}">{$SETTINGS.$TAB[setting].value|default:$SETTINGS.$TAB[setting].default}</textarea>
              {else}
                Unknown option type: {$SETTINGS.$TAB[setting].type}
              {/if}
              </div>
              {/section}
            </div>
            {/foreach}
          </div>
          
        </div>
        <div class="panel-footer">
          <input type="submit" value="Save" class="btn btn-success btn-sm">
        </div>
      </form>
    </div>
  </div>
</div>