  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-dot-circle-o fa-fw"></i> Pool Information
        </h4>
      </div>
      <div class="panel-footer">
       <div class="row">
          {* Depending on the price option we need to load a different template so it aligns properly *}
          {if $GLOBAL.config.price.enabled}
          {include file="dashboard/overview/_with_price_graph.tpl"}
          {else}
          {include file="dashboard/overview/_without_price_graph.tpl"}
          {/if}
       </div>
      </div>
    </div>
  </div>
