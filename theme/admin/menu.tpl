{if $user}
    Welcome: {$user.name}<br />
    <a href="/{$controllers.admin}.html?func=logout">Logout</a>

    {if $chapter_data}
        {foreach from=$chapter_data key=chapter item=controller_menu}
            <h2>{$chapter}</h2>
            {if $controller_menu.controllers}
                {foreach from=$controller_menu.controllers key=controller item=one_controller}
                    <h3>{$one_controller.title}</h3>
                    {if $one_controller.tables}
                        <div style="padding-top:10px;">
                            {foreach from=$one_controller.tables key=table item=one_table}
                                <div>
                                    <a href="/{$controllers.admin}.html?con={$controller}&table={$table}" class="ChangeLocation">{$one_table.title}</a>
                                </div>
                            {/foreach}
                        </div>
                    {/if}
                {/foreach}
            {/if}
        {/foreach}
    {/if}
{/if}