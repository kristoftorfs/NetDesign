{$form.start}
<div class="pageoverflow">
    <p class="pageinput">
        {$form.submit}{$form.cancel}
    </p>
</div>
{foreach $settings as $name => $items}
    <fieldset>
        <legend>{$name|escape}</legend>
        {foreach $items as $setting}
            <div class="pageoverflow">
                <p class="pagetext">{$setting.caption|escape}</p>
                <p class="pageinput">{$setting.input}</p>
            </div>
        {/foreach}
    </fieldset>
{/foreach}
{$form.end}