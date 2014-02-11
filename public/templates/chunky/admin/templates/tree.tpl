<ul>
  {foreach from=$files item="value" key="file"}
  {if is_array($value)}
    <li class="folder">
      {$file}
    {assign var="new_prefix" value="$prefix$file/"}
    {include file="admin/templates/tree.tpl" files=$value prefix=$new_prefix}
    </li>
  {else}
    {assign var="path" value="$prefix$file"}

    {assign var="classes" value=array()}
    {if array_key_exists($path, $ACTIVE_TEMPLATES)}
      {assign var="tmp" value=array_push($classes,"dynatree-activated")}
    {/if}
    {if $CURRENT_TEMPLATE eq $path}
      {assign var="tmp" value=array_push($classes,"dynatree-active")}
    {/if}
    {assign var="classes" value=join(" ", $classes)}
    <li{if $classes} class="{$classes}" data="addClass:'{$classes}'{if strpos("dynatree-active", $classes) !== false}, activate: true{/if}"{/if}>
      <a href="{$smarty.server.PHP_SELF}?page={$smarty.request.page}&action={$smarty.request.action}&template={$prefix}{$file}">{$file}</a>
    </li>
  {/if}
  {/foreach}
</ul>
