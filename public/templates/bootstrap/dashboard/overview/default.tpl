  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-align-left fa-fw"></i> Overview</span></h4>
      </div>
      <div class="panel-body text-center">
       <div class="row show-grid">
          {* Depending on the price option we need to load a different template so it aligns properly *}
          {if $GLOBAL.config.price.enabled}
          {include file="dashboard/overview/_with_price_graph.tpl"}
          {else}
          {include file="dashboard/overview/_without_price_graph.tpl"}
          {/if}
       </div>
      </div>
      <div class="panel-footer overview">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
  </div>
