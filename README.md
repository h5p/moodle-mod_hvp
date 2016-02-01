# H5P Moodle Plugin

This is a prototype under development.

## Install

### Development Version
Warning! Using the development version may cause strange bugs, so do not use it for production!

Inside your `moodle/mod` folder you run the following command:
```
git clone https://github.com/h5p/h5p-moodle-plugin.git hvp && cd hvp && git submodule update --init
```

### Alpha Versions
If you can't wait for the final release you can check out one of the test versions.
Here is an example. Remember to replace the version number with the latest from the [releases](https://github.com/h5p/h5p-moodle-plugin/releases) page:
```
git clone --branch 0.2.0 https://github.com/h5p/h5p-moodle-plugin.git hvp && cd hvp && git submodule update --init
```

Alternatively, you can download the latest tag/version from the [releases](https://github.com/h5p/h5p-moodle-plugin/releases) page.
However, then you'll also have to download the same version of [h5p-php-library](https://github.com/h5p/h5p-php-library/releases),
e.g. *moodle-0.2.0*. And extract it into the `moodle/mod/hvp/library` folder.

### Enabling The Plugin
In Moodle, go to administrator -> plugin overview, and press 'Update database'.

## Settings

Settings can be found at: Site Administration -> Plugins -> Activity Modules -> H5P

## Contributing

Feel free to contribute by:
* Submitting translations
* Testing and creating issues. But remember to check if the issues is already reported before creating a new one.
Perhaps you can contribute to an already existing issue?
* Solving issues and submitting code through Pull Requests.
