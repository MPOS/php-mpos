{if is_array($smarty.session.POPUP|default)}
  {section popup $smarty.session.POPUP}
    <div class="{$smarty.session.POPUP[popup].TYPE|default:"alert alert-info"}">
      {if $smarty.session.POPUP[popup].TYPE == "errormsg"}
        <h4><i class="fa fa-ban"></i> <strong>Uh oh! You got an error!</strong></h4>
      {/if}

      {$smarty.session.POPUP[popup].CONTENT}
    </div>
  {/section}
{/if}
