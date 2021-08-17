# Backup & Restore
When I started the "Backup and restore" section of the induction process for Moodle, I saw that the documentation was missing few important points for backing up plugins related to a course. So this documentation is to make it easier for future students.

## Folder structure
This is the easy part. All the files we will create for the backup and for the restore will be placed in the same directory, which is `our_plugin_folder/backup/moodle2`, so we will proceed to create the structure.

Now that we have our folder, we will continue with the next steps.

## Naming
During the whole process we will find moments where the name of the files/classes/functions/etc will change depending on what we are creating the backup for. Keeping that in mind, here is a list of the names used and their possibilities:
1. `{action}`: this part indicates the action we want to perform with this file. It will be either `backup` or `restore`.
2. `{plugintype}`: this part indicates the plugin type of the current plugin. It can be `tool`, `mod`, `theme`, etc. A full list of plugin types can be found here: https://docs.moodle.org/dev/Plugin_types
3. `{pluginname}`: this part is the name you gave to the plugin.
4. `{elementtype}`: this part indicates the element you are trying to backup. This can be either `plugin`, `subplugin` or `task` (there might be more, but those are the ones I was able to find).
5. `{belongsto}`: this part defines what element the plugin belongs to. In other words, with the backup of what element, this plugin need to be backed up. That element is the one for which the plugin is designed to.

## Backup
This will be the first real part of the process (Mainly because if we don't have a backup, we can't do the restore).

### File
Inside the folder `moodle2` we previously created, we need to create a `*.class.php` file with the following name format `{action}_{plugintype}_{pluginname}_{elementtype}.class.php`. Note that the name of the file is VERY important and it's divided in 4 parts. The points 1, 2, 3 and 4 from the naming section.

So, in our case, the file name will be `backup_tool_odeialba_plugin.class.php`.

### Code
It's already time to start working on our backup.

In the file we just created, we need to require (after all the copyright text) the file with the abstract class that will help us with the backup. The naming of this file follows the pattern from the naming section, using the 1, 2 and 4 points of the list.

For our example it would look like this:
```php
// Copyright stuff ...
require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');
```
NOTE: Please, make sure that the file exists before requiring it.

After that, we will create a class with the same name of the file, extending the class in the file we required.

Our example code:
```php
// Require stuff ...
class backup_tool_odeialba_plugin extends backup_tool_plugin {
```

Inside this class we will need only one protected function. It will be the one in charge of backing up everything related to our plugin and will return a `backup_{elementtype}_element` (in our case it will be `backup_plugin_element`). The name of that function will be `define_{belongsto}_{elementtype}_structure`.

So our functions should look like this:
```php
// Class stuff ...
protected function define_course_plugin_structure() {
```

Now, inside this function we will start preparing everything that we want in the backup, meaning the data from the database and any uploaded file. For that we will use `backup_nested_element` object, passing three parameters:
1. The first one will be the name of the table in the database.
2. The second one will be an array with the name of the primary key columns (*I am not really sure about this one. I tried passing null and it worked anyway*).
3. The third one will be an array with the rest of the columns of the table.

So far we have this:
```php
// Protected function stuff ...
$tabledata = new backup_nested_element(
    'tool_odeialba',
    ['id'],
    [
        'courseid',
        'name',
        'completed',
        'priority',
        'timecreated',
        'timemodified',
        'description',
        'descriptionformat'
    ]
);
```

Since this plugin depends on a course and the backup of the plugin will be executed when the course is backed up (and the database table has a foreign `courseid` pointing to the `id` column of the courses table), we now need to define that using `set_source_table` and passing two parameters:
1. The database table of our plugin.
2. An array with the column of foreign key as the key and the constant of the reference.

This would be our case:
```php
// backup_nested_element stuff ...
$tabledata->set_source_table('tool_odeialba', ['courseid' => backup::VAR_COURSEID]);
```

If the plugin also contains uploaded files, we also want them in the backup. We will add them using `annotate_files` and passing three/four parameters to it:
1. The component name, which is `{plugintype}_{pluginname}`.
2. The name of the filearea you defined in the form.
3. Since it is possible to have multiple file areas in the same element (table), you may end up having multiple calls to the annotate_files() method, one for each filearea to be added to the backup. The third parameter, if it is needed, must be the name of one of the attributes or fields of the $tabledata element (usually, in the vast majority of cases, the 'id' of the element), otherwise we'll use null.
4. The fourth parameter is optional and will default to the context id of the backup, but if you want to specify the context id you do so here.
```php
// set_source_table stuff ...
$tabledata->annotate_files('tool_odeialba', 'file', null);
```

Now that we defined everything that we want to keep in the backup, we need to return it. As we said before, this function will return a `backup_plugin_element` object, so we need to create it. To do so, we will call `get_plugin_element` which will create the object for us:
```php
// annotate_files stuff ...
$plugin = $this->get_plugin_element();
```

We have the object, but it doesn't have any data defined. So if we restored it now, our plugin data would be empty. To avoid that, we need to assign all the data we prepared for the backup to this object:
```php
// get_plugin_element stuff ...
$plugin->add_child($tabledata);
```

And finally, we will return the object:
```php
// add_child stuff ...
return $plugin;
```

(Don't forget to close all the curly brackets you opened)

Now that we created the backup file for the plugin, whenever we back up the course, all the plugin's data will be backed up as well.

## Restore
If you already created a backup of a course and tried to restore it, you might have realised that the data of the plugin is not there. To be able to restore the plugin's data, we also need to create all the functionality to do so.

### File
We will follow the same file naming as the backup file with the only difference of changing `backup` to `restore`.

So in the same directory we will create the file `restore_tool_odeialba_plugin.class.php`.

### Code
It's time to restore our previous backup.

In the file we just created, we need to require (after all the copyright text) the file with the abstract class that will help us with the backup. The naming of this file follows the pattern from the naming section, using the 1, 2 and 4 points of the list.

For our example it would look like this:
```php
// Copyright stuff ...
require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');
```
NOTE: Please, make sure that the file exists before requiring it.

After that, we will create a class with the same name of the file, extending the class in the file we required.

Our example code:
```php
// Require stuff ...
class restore_tool_odeialba_plugin extends restore_tool_plugin {
```

Inside this class we will need three functions. The first one will be the one defining the paths (`restore_path_element` object) to restore the plugin's data and will return an array with those paths. The name of that function will be `define_{belongsto}_{elementtype}_structure`.

So our first function should be protected and should look like this:
```php
// Class stuff ...
protected function define_course_plugin_structure() {
```

(So far this file looks very similar to the one that we created for the backup, but now we will start seeing the difference.)

Within this new function we need to prepare and return the array with the `restore_path_element` objects. In our case the array will only contain one element. The constructor of that object needs two parameters:
1. The name of the thing being restored (we will use `{plugintype}_{pluginname}`). This determines the name of the process_... method called (will be created in the next step).
2. The path of the element to restore. The format will be `/{belongsto}/{plugintype}_{pluginname}`.

The content of our function will be this:
```php
// Protected function stuff ...
$paths = [];
$paths[] = new restore_path_element('tool_odeialba', '/course/tool_odeialba');

return $paths;
```

We can also make it shorter doing:
```php
// Protected function stuff ...
return [new restore_path_element('tool_odeialba', '/course/tool_odeialba')];
```

Now we will create the second function which will be public and will be in charge of restoring the data from the backup. For the name of this function we will use `process_` and the first variable that we passed to `restore_path_element` constructor. So it would look `process_{plugintype}_{pluginname}`. In our case `process_tool_odeialba`. This function will only need one parameter:
1. Array or object of the single element to restore.

Our code would look like this:
```php
// End of previous function ...
public function process_tool_odeialba($data) {
```

Before doing anything within this function, we need to prepare the variables we need. Since we are planning on inserting the backup data into the database, we will declare the global `$DB` variable. Then, as we are not sure whether `$data` is an array or an object, we will use it as an object and we will make sure that it really is one.
```php
// Public function stuff ...
global $DB;
$data = (object) $data;
```

As the `id` of the element to insert in the database will be new and there might be "stuff" related to the old `id`, we need to save the old `id` in a variable, so we can later define which old `id` belongs to which new `id` (we will do this later within this same function):
```php
// Necessary variables stuff ...
$oldid = $data->id;
```

Now is a crucial moment. If we want to replace any information of the element that we are restoring by something different, we can now change it in the properties of the `$data` variable. As the `id` of the course might have changed during the restore, we need to make sure that `courseid` keeps the new course `id`. To do so, we will get the new course `id` from the task and pass it to the `courseid` property of our `$data` variable. This way you can also change any other information of the new restored record:
```php
// old id stuff ...
$data->courseid = $this->task->get_courseid();
```

It's time for the moment we were all waiting for! It's time to restore our data! To do that we will just insert in out database table everything in the `$data` variable. As you might know, `insert_record` returns the id of the newly inserted record, so we will need to pass that value to our `$data->id`:
```php
// course id stuff ...
$data->id = $DB->insert_record('tool_odeialba', $data);
```

As the last step of this function, we need to map the old `id` of the record to the new one and point out that the files also need to be restored. We will do that calling `set_mapping` with four parameters:
1. The name of item being restored (`{plugintype}_{pluginname}`).
2. The old `id` that we saved in a variable earlier.
3. The new `id` that we replaced in `$data->id`.
4. Optional boolean to define whether we want to restore files or not (default to `false`).

It will look like this:
```php
// insert stuff ...
$this->set_mapping('tool_odeialba', $oldid, $data->id, true);
```

Now that we have restored the data related to the plugin, we need to use one last protected function to restore the files as well. This function will be called after the restore is completed. The naming of this function is `after_{action}_{belongsto}` and does not contain any parameter.

Our code would look like this:
```php
// End of previous function ...
protected function after_restore_course() {
```

This function will only have one call to `add_related_files` with three parameters:
1. The name of the component being restored (we use the format `{plugintype}_{pluginname}`).
2. The filearea name (same as the one we used in the backup process).
3. The name of the item (same as the one we used in the last step of the previous function).

Our code will end up like this:
```php
// Protected function stuff ...
$this->add_related_files('tool_odeialba', 'file', 'tool_odeialba');
```

(Don't forget to close all the curly brackets you opened)

## Conclussion
Following all the steps of this documentation should lead to a successful backup and restore of a simple plugin. If it didn't work or if the information here wasn't enough, head to the original documentations:
1. https://docs.moodle.org/dev/Backup_API
2. https://docs.moodle.org/dev/Backup_2.0_for_developers
3. https://docs.moodle.org/dev/Restore_API
4. https://docs.moodle.org/dev/Restore_2.0_for_developers
