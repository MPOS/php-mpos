{section name=news loop=$NEWS}
<div data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="true">
    <h3>{$NEWS[news].header}</h3>
    <p>{$NEWS[news].content}</p>
  </div>
</div>
{/section}
