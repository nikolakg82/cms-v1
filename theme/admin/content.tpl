{extends file=$admin_theme|cat:"/admin/skel.tpl"}
{block name=skel_data}
    {block name=header}
        {include file=$admin_theme|cat:'admin/header.tpl'}
    {/block}
    <div class="Table Full">
        <div class="TableCell LeftCell Top">
            <div class="Content">
                {block name=menu}
                    {include file=$admin_theme|cat:'admin/menu.tpl'}
                {/block}
            </div>
        </div>
        <div class="TableCell MainCell Top">
            <div class="Content">
                {block name=main_data}{/block}
            </div>
        </div>
    </div>
    {block name=footer}
        {include file=$admin_theme|cat:'admin/footer.tpl'}
    {/block}
{/block}