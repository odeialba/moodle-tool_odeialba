#Backup & Restore
When I started the "Backup and restore" section of the induction process for Moodle, I saw that the documentation was missing few important points for backing up plugins related to a course. So this documentation is to make it easier for future students.

##Folder structure
This is the easy part. All the files we will create for the backup and for the restore will be placed in the same directory, which is `our_plugin_folder/backup/moodle2`, so we will proceed to create the structure.

Now that we have our folder, we will continue with the next steps.

##Naming
During the whole process we will find moments where the name of the files/classes/functions/etc will change depending on what we are creating the backup for. Keeping that in mind, here is a list of the names used and their possibilities:
1. `{action}`: this part indicates the action we want to perform with this file. It will be either `backup` or `restore`.
2. `{plugintype}`: this part indicates the plugin type of the current plugin. It can be `tool`, `mod`, `theme`, etc. A full list of plugin types can be found here: https://docs.moodle.org/dev/Plugin_types
3. `{pluginname}`: this part is the name you gave to the plugin.
4. `{elementtype}`: this part indicates the element you are trying to backup. This can be either `plugin`, `subplugin` or `task` (there might be more, but those are the ones I was able to find).
5. `{belongsto}`: this part defines what element the plugin belongs to. In other words, with the backup of what element, this plugin need to be backed up. That element is the one for which the plugin is designed to.

##Backup
This will be the first real part of the process (Mainly because if we don't have a backup, we can't do the restore).

###File
Inside the folder `moodle2` we previously created, we need to create a `*.class.php` file with the following name format `{action}_{plugintype}_{pluginname}_{elementtype}.class.php`. Note that the name of the file is VERY important and it's divided in 4 parts. The points 1, 2, 3 and 4 from the naming section.

So, in our case, the file name will be `backup_tool_odeialba_plugin.class.php`.

###Code
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
$tabledata->annotate_files('tool_odeialba', 'file', null);
```

Now that we defined everything that we want to keep in the backup, we need to return it. As we said before, this function will return a `backup_plugin_element` object, so we need to create it. To do so, we will call `get_plugin_element` which will create the object for us:
```php
$plugin = $this->get_plugin_element();
```

We have the object, but it doesn't have any data defined. So if we restored it now, our plugin data would be empty. To avoid that, we need to assign all the data we prepared for the backup to this object:
```php
$plugin->add_child($tabledata);
```

And finally, we will return the object:
```php
return $plugin;
```

(Don't forget to close all the curly brackets you opened)

Now that we created the backup file for the plugin, whenever we back up the course, all the plugin's data will be backed up as well.

##Restore
If you already created a backup of a course and tried to restore it, you might have realised that the data of the plugin is not there. To be able to restore the plugin's data, we also need to create all the functionality to do so.

###File
We will follow the same file naming as the backup file with the only difference of changing `backup` to `restore`.

So in the same directory we will create the file `restore_tool_odeialba_plugin.class.php`.




