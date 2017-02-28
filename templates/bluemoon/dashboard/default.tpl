{if $smarty.session.AUTHENTICATED|default}
<script src="{$GLOBALASSETS}/js/plugins/date.format.js"></script>
<script src="{$GLOBALASSETS}/js/plugins/soundjs-0.6.0.min.js"></script>
<div class="left-sidebar">
  <div class="row">
    <div class="col-lg-12">
      <div class="widget">
        <div class="widget-header">
          <div class="title">
            Overview
          </div>
          <span class="tools">
            <i class="fa fa-align-left"></i>
          </span>
        </div>
        <div class="widget-body text-center">
          <div class="row">
            <div class="col-lg-12">
              {* Load our payout system so we can load some payout specific templates *}
              {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
              {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/default.tpl"}
              {if !$DISABLED_API}
                <div class="row">
                  <div class="col-lg-8">
                    {include file="dashboard/blocks/default.tpl"}
                  </div>
                  <div class="col-lg-4">
                    {include file="dashboard/worker_information/default.tpl"}
                  </div>
                </div>
              {else}
                <div class="row">
                  <div class="col-lg-12">
                    {include file="dashboard/blocks/default.tpl"}
                  </div>
                </div>
              {/if}
            </div>
          </div>
          <div class="pull-left">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        
<div class="right-sidebar">
{include file="dashboard/overview/default.tpl"}
<hr class="hr-stylish-1">
{include file="dashboard/account_data/default.tpl"}
</div>

{* Include our JS libraries, we allow a live updating JS and a static one *}
{if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
{include file="dashboard/js/api.tpl"}
{else}
{include file="dashboard/js/static.tpl"}
{/if}
{/if}

