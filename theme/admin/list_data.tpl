{extends file=$admin_theme|cat:"/admin/skel.tpl"}

{block name=skel_data}
    <div>
        <a href="/{$controllers.admin}.html?con={$con}&table={$table}&action=new{if $sid}&item_sid={$sid}{/if}">Dodaj novo</a>
    </div>
    {if $data.data}
        <div class="Table Full">
            <div class="TableRow">
                {foreach from=$table_data item=table_field}
                    <div class="TableCell">{$table_field.title}</div>
                {/foreach}
                <div class="TableCell">Action</div>
            </div>
            {foreach from=$data.data item=data}
                <div class="TableRow">
                    {foreach from=$table_data key=key item=table_field}
                        <div class="TableCell">
                            {if $table_field.type == 'date'}
                                {$data.$key|date_format:"%d.%m.%Y."}
                            {else}
                                {$data.$key}
                            {/if}
                        </div>
                    {/foreach}
                    <div class="TableCell"><a href="/{$controllers.admin}.html?con={$con}&table={$table}&action=edit&item_id={$data.id}&show=container{if $sid}&sid={$sid}{/if}">Edit</a></div>
                </div>
            {/foreach}
            <div class="TableRow">
                {foreach from=$table_data item=table_field}
                    <div class="TableCell">{$table_field.title}</div>
                {/foreach}
                <div class="TableCell">Action</div>
            </div>
        </div>
        {if $data.pagination}
            {block name=pagination}
                {include file='structure/pagination.tpl'}
            {/block}
        {/if}
    {/if}
{/block}