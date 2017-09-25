{locale path="nextgen/locale" domain="partdb"}
{if isset($refresh_navigation_frame) && $refresh_navigation_frame}
    <script type="text/javascript">
        AjaxUI.getInstance().updateTrees();
    </script>
{/if}

<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-archive" aria-hidden="true"></i>
        {t}Baugruppen{/t}
    </div>
    <div class="panel-body">
        <form action="" method="post" class="row no-progbar">
            <div class="col-md-4">

                {if !isset($id) || $id == 0}
                    {assign "can_edit" $can_create}
                    {assign "can_move" $can_create}
                {/if}

                <select class="form-control selectpicker"  data-live-search="true" onChange='$("[name=selected_id]").val(this.value); submitForm(this.form);'>
                    <optgroup label="{t}Neu{/t}">
                        <option value="0" {if !isset($id) || $id == 0}selected{/if}>{t}Neue Baugruppe{/t}</option>
                    </optgroup>
                    <optgroup label="{t}Bearbeiten{/t}">
                        {$device_list nofilter}
                    </optgroup>
                </select>

                <hr>

                <select name="selected_id" size="30" class="form-control" onChange="submitForm(this.form);">
                    <optgroup label="{t}Neu{/t}">
                        <option value="0" {if !isset($id) || $id == 0}selected{/if}>{t}Neue Baugruppe{/t}</option>
                    </optgroup>
                    <optgroup label="{t}Bearbeiten{/t}">
                        {$device_list nofilter}
                    </optgroup>
                </select>
            </div>
               
            <div class="col-md-8 form-horizontal">
                <fieldset>
                <legend>
                        {if !isset($id) || $id == 0}
                            <strong>{t}Neue Baugruppe hinzufügen:{/t}</strong>
                        {else}
                            {if isset($name)}
                                <strong>{t}Baugruppe bearbeiten:{/t} <a href="show_device_parts.php?device_id={$id}">{$name}</a></strong>
                            {else}
                                <strong>{t}Es ist keine Baugruppe angewählt!{/t}</strong>
                            {/if}
                        {/if}
                </legend>
                
                <div class="form-group">
                    <label class="control-label col-md-3">{t}ID:{/t}</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{if isset($id)}{$id}{else}-{/if}</p>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-md-3">{t}Name*:{/t}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="name" value="{$name}"
                               placeholder="{t}z.B. Transistortester{/t}" required {if !$can_edit}disabled{/if}>
                    </div>
                </div>
            
               <div class="form-group">
                    <label class="control-label col-md-3">{t}Übergeordnete Baugruppe*:{/t}</label>
                    <div class="col-md-9">
                        <select class="form-control selectpicker" data-live-search="true" name="parent_id" size="1" {if !$can_move}disabled{/if}>
                            {$parent_device_list nofilter}
                        </select>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="col-md-9 col-md-offset-3">
                        <i>* = {t}Pflichtfelder{/t}</i>
                    </label>
                </div>
                
                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        {if !isset($id) || $id == 0}
                            <button class="btn btn-success" type="submit" name="add" {if !$can_create}disabled{/if}>{t}Neue Baugruppe anlegen{/t}</button>
                            <div class="checkbox">
                                <input type="checkbox" name="add_more" {if $add_more}checked{/if} {if !$can_create}disabled{/if}>
                                <label>{t}Weitere Baugruppen anlegen{/t}</label>
                            </div>
                        {else}
                            <button class="btn btn-success" type="submit" name="apply" {if !$can_edit && !$can_move}disabled{/if}>{t}Änderungen übernehmen{/t}</button>
                            <button class="btn btn-danger" type="submit" name="delete" {if !$can_delete}disabled{/if}>{t}Baugruppe löschen{/t}</button>
                        {/if}
                    </div>
                </div>
            </fieldset>
            </div>
        </form>
    </div>
</div>