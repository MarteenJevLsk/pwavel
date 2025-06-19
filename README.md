# PwaVel
![v.1.6.8](https://img.shields.io/badge/v.1.6.8-432959?style=for-the-badge)

## Installation

    composer require --dev marteen/pwavel

### note: in a production environment, avoid using `--dev`

## Publish components and Install PWA

1. Run the following command to publish config file,


    ```php artisan pwavel:publish```


 2. Add following code in root blade file in header section.

    ```@include('partials.manifest')```

3. Add following code in root blade file in before close the body:

    ```@include('partials.metaInf')```


### License
The MIT License (MIT). Please see [License](LICENSE) File for more information   