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
            directories:           ["%kernel.root_dir%/../src"]
```

