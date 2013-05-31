{if $GLOBAL.motd|default}
      <div id="generic_infobox" style="margin-left:3px;">
        <font color="orange" size="1">***Message of the Day***<br></font>
        <font size="1">{$GLOBAL.motd|escape:'html'}</font>
      </div>
{/if}
