 <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          <i class="fa fa-question fa-fw"></i> Setup Checks<br />
          <i>To disable these checks, set skip_config_tests to true in global.inc.php</i>
        </div>
        <div class="panel-body">
        {if $ERRORS|@count > 0}
          {section errors $ERRORS}
            <p><strong>{$ERRORS[errors].name}</strong></p>
            <p>{$ERRORS[errors].description}</p>
            <p>See: <a href="{$ERRORS[errors].helplink}">{$ERRORS[errors].configvalue}</a></p>
            <hr />
          {/section}
        {/if}
        </div>
      </div>
    </div>
    <!-- /.col-lg-12 -->
  </div>
