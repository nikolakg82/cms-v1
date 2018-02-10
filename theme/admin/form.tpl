<form method="post" action="{$form_action}" enctype="multipart/form-data">
    {foreach from=$form_data key=field item=form_field}
        {if $form_field.type == 'text' || $form_field.type == 'numeric' || $form_field.type == 'date' || $form_field.type == 'select' || $form_field.type == 'textarea' || $form_field.type == 'switch' || $form_field.type == 'texteditor'}
            <div>
                {$form_field.title}
            </div>
            <div>
                {if $form_field.type == 'text' || $form_field.type == 'date' || $form_field.type == 'numeric'}
                    <input type="{if $form_field.type == 'numeric'}number{else}text{/if}" name="{$field}" value="{if $form_field.value || $form_field.value == '0'}{$form_field.value}{else}{$form_field.default_value}{/if}" {if $form_field.min_value || $form_field.min_value == '0'}min="{$form_field.min_value}"{/if} {if $form_field.max_value}max="{$form_field.max_value}"{/if} class="{if $form_field.type == 'date'} Date{/if}"{if $form_field.type == 'date'} readonly{/if} />
                {elseif $form_field.type == 'textarea' || $form_field.type == 'texteditor'}
                    <textarea name="{$field}">{if $form_field.value || $form_field.value == '0'}{$form_field.value}{else}{$form_field.default_value}{/if}</textarea>
                {elseif $form_field.type == 'select'}
                    <select name="{$field}">
                        <option value="">Select</option>
                        {if $form_field.values}
                            {foreach from=$form_field.values item=option}
                                <option value="{$option.id}"{if $form_field.value == $option.id} selected{/if}>{$option.title}</option>
                            {/foreach}
                        {/if}
                    </select>
                {elseif $form_field.type == 'switch'}
                    <input type="checkbox" name="{$field}" {if $form_field.value == 'y'}checked{/if} />
                {/if}
            </div>
        {elseif $form_field.type == 'auto'}
            {if $form_field.value}
                <input type="text" value="{$form_field.value}" name="{$field}" />
            {/if}
        {/if}
    {/foreach}
    <input type="submit" value="Submit" />
</form>