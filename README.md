# Importer for Statamic ![Statamic v2](https://img.shields.io/badge/statamic-v2-blue.svg?style=flat-square)

CLI commands to create import content from a database. Import will import a single entry from the ID given. 
Import_span will import a span of entries. Import_all will import all entries.

## Usage
- Install the addon by copying the files into `site/addons/Importer`.
- Setup the database structure in the addon settings in the control panel.
- Add the following with connection information to your .env
```
DB_HOST=database_host
DB_DATABASE=database_name
DB_USERNAME=database_username
DB_PASSWORD=database_password
```

- Run the `please` command.

## Commands
Running the `please` commands without any arguments will give you interactive prompts to help you import a piece of content.

```
php please importer:import [<id>] [<folder>]
php please importer:import_span [<folder>] [<first>] [<last>]
php please importer:import_all [<folder>] 
```

Thanks to the Gentlemen for the Overload plugin which got me started.

## Warning
This can be destructive and is not meant to be a perfect import. Assume that you will have to touch each piece of content.

You will need to publish the content after.