# Steps to made a plugin

## Create a folder with the same name of the folder

and the file index.php - Silence is golden

- Create composer.json with composer init and define a vendor
- Create a class for the new plugin with a constructor function
- Create a inner function to define constants for path, url and version

## Add Auto load via composer

- Composer init
- add psr 4
  "psr-4": {
  "Mager19\\CustomAcfColumns\\": "src/"
  }
- be sure the namespace is equal to the name de class and name of file
- Import in the main file autoload
  require_once **DIR** . '/vendor/autoload.php';

## Create a class for the admin page
