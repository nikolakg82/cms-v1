{extends file=$admin_theme|cat:"/admin/skel.tpl"}
{block name=skel_data}
    {include file=$admin_theme|cat:'admin/form.tpl' form_data=$form_item_data}
    {if $mlc_data && $langs}
        MLC DATA
        <div>
            {foreach from=$langs key=key_lang item=one_lang}
                <div>
                    {$one_lang.name}
                    <div>
                        {include file=$admin_theme|cat:'admin/form.tpl' form_data=$mlc_data.$key_lang form_action=$form_action|cat:'&mlc_edit=ok'}
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}
{/block}