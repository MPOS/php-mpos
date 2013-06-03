<div class="block{if $ALIGN|default} small {$ALIGN}{/if}" style="{if $BLOCK_STYLE|default}{$BLOCK_STYLE}{else}clear:none;{/if}">
  <div class="block_head">
    <div class="bheadl"></div>
    <div class="bheadr"></div>
    <h2>{$BLOCK_HEADER|default:"UNKNOWN BLOCK"}</h2>
    {if $BUTTONS|default}
    <ul class="tabs">
      {foreach from=$BUTTONS item=name}
      <li style="font-size:9px;"><a href="#{$name}">{$name}</a></li>
      {/foreach}
    </ul>
    {/if}
  </div>
  <div class="block_content" style="{if $STYLE|default}{$STYLE}{else}padding:10px;{/if}">
