{extends file=$admin_theme|cat:"/admin/skel.tpl"}
{block name=skel_data}
    {if $container_data}
        {foreach from=$container_data item=one_container}
            <a href="{$one_container.path}" class="ChangeEditFrame">{$one_container.title}</a>
        {/foreach}
    {/if}

    <iframe src="{$container_data.0.path}" id="Editframe">

    </iframe>
{/block}