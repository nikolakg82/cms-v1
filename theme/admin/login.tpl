{extends file=$admin_theme|cat:"/admin/skel.tpl"}
{block name=skel_data}
    {if $message}
        <div>
            {$message}
        </div>
    {/if}
    <div class="Table Auto">
        <form method="post" action="/{$controllers.admin}.html?func=submit">
            <div>
                <input type="text" name="username" placeholder="Username" required />
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <div>
                <input type="submit" value="Go" />
            </div>
        </form>
    </div>
{/block}