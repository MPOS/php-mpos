        {if !$DISABLED_DASHBOARD and !$DISABLED_DASHBOARD_API}
        <table class="table table-bordered table-hover table-striped"> 
         <thead>
          <tr>
            <th>{t}Worker{/t}</th>
            <th>{t}Hashrate{/t}</th>
            <th>{t}Difficulty{/t}</th>
          </tr>
          </thead>
          <tbody id="b-workers">
            <td colspan="3" class="text-center">{t}No worker information available{/t}</td>
          </tbody>
          </tr>
        </table>
        {/if}
