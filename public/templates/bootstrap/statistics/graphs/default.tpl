<article class="module width_full">
  <header>
    <h3 class="tabs_involved">Stats</h3>
    <ul class="tabs">
        <li><a href="#mine">Mine</a></li>
        <li><a href="#pool">Pool</a></li>
        <li><a href="#both">Both</a></li>
    </ul>
  </header>
  <div class="tab_container">
{include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/mine.tpl"}
{include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/pool.tpl"}
{include file="{$smarty.request.page|escape}/{$smarty.request.action|escape}/both.tpl"}
  </div>
</article>
