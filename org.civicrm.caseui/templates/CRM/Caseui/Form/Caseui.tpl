{$form.buttons.html}
{$form.case_type_name.label}
{$form.case_type_name.html}
<h2>Activity Types</h2>
<table><tr><th>Activity Type</th><th>Maximum Instances</th></tr>
    {foreach from=$options key=k item=v}
        <tr>
            <td><label><input type="checkbox" name="activity-types[{$v}][value]" value="{$v}"/>{$v}</label></td>
            <td><input type="number" name="activity-types[{$v}][instance]" /></td>
        </tr>
    {/foreach}
</table>

<h2>Activity Sets</h2>
<table>
    <tr>
        <td><label>Activity Set Name: </label><input type="text" name="activity-sets[0][activitySetName]" /></td>
        <td><label>Activity Set Label: </label><input type="text" name="activity-sets[0][activitySetLabel]" /></td>
        <td><label>Timeline ?: </label>{html_radios name="activity-sets[0][timeLineBool]" options=$timelineOpts}</td>
    </tr>
    <tr>
        <td><label>Global Reference Activity: </label>{html_options name="activity-sets[0][globalRefActivity]" options=$options}</td>
        <td><label>Status Of Global Activity: </label>{html_options name="activity-sets[0][refActivityStatus]" options=$statusOptions}</td>
    </tr>
    <tr><table><tr><th>Activity Type</th><th>Reference Offset</th><th>Reference Select</th></tr>
   {foreach from=$options key=k item=v}
        <tr>
            <td><label><input type="checkbox" name="activity-sets[0][set][{$v}][value]" value="{$v}"/>{$v}</label></td>
            <td><input type="number" name="activity-sets[0][set][{$v}][offset]" /></td>
            <td>
                <select name='activity-sets[0][set][{$v}][refSelect]'>
                    {foreach from=$refSelectOpts key=optk item=optv}
                        <option value={$optk}>{$optv}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
    {/foreach}
    </table></tr>
</table>
    
    
<h2>Case Roles</h2>
<table>
    <tr><th>Relationship Types</th><th>Creator</th><th>Manager</th></tr>
        {foreach from=$relationshipTypes item=value}
            <tr>
                <td><label><input type="checkbox" name="relationship-type[{$value.label_b_a}][value]" value="{$value.label_b_a}"/>{$value.label_b_a}</label></td>
                <td><input type="checkbox" name="relationship-type[{$value.label_b_a}][creator]" value="1"/></td>
                <td><input type="checkbox" name="relationship-type[{$value.label_b_a}][manager]" value="1" /></td>
            </tr>
        {/foreach}
</table>
    


