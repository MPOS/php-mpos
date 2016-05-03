 <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <i class="fa fa-question fa-fw"></i> Setup Checks<br />
        </div>
        <div class="panel-body">
        {if $ERRORS|@count > 0}
          {section errors $ERRORS}
           <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-{if $ERRORS[errors].level >= 2}danger
              {elseif $ERRORS[errors].level == 1}warning
              {elseif $ERRORS[errors].level == 0}info{/if}">
                <div class="panel-heading">
                  <i class="fa fa-{if $ERRORS[errors].level >= 2}times-circle
                                  {elseif $ERRORS[errors].level == 1}warning
                                  {elseif $ERRORS[errors].level == 0}info{/if} fa-fw"></i> <strong>{$ERRORS[errors].name}</strong>
                </div>
                <div class="panel-body">
                  <p>{$ERRORS[errors].description}</p>
                  <p><pre style='width:35%'>$config.<a href="{$ERRORS[errors].helplink}">{$ERRORS[errors].configvalue}</a></pre></p>
                  <p>{$ERRORS[errors].extdesc}</p>
                </div>
              </div>
            </div>
            <!-- /.col-lg-12 -->
          </div>
          {/section}
        {/if}
        </div>
        <div class="panel-footer">
		<li>To disable these checks, set skip_config_tests to true in global.inc.php</li>
	</div>
      </div>
    </div>
    <!-- /.col-lg-12 -->
  </div>
