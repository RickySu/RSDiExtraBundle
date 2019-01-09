Configuration
=============

config/services.yml

```yaml
services:

rs_di_extra:
    locations:
        all_bundles:           false
        bundles:               [FooBundle, AcmeBlogBundle]
        disallow_bundles:      [BarBundle]
        directories:           ["%kernel.project_dir%/../src"]
        exclude_directories:   
            -                  foo
            -                  bar
        exclude_files:
            -                  '*Test.php'

```

