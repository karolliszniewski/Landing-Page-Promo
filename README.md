### Steps to Set Up the Module

1. **Create the Module Directory Structure:**
    - Navigate to `app/code` and create a new folder named `LandingPage`.
    - Inside `app/code/Ecommerce`, create another folder named `Form`.

2. **Create the `etc` Directory:**
    - Within `app/code/LandingPage/Form`, create a folder named `etc`.

3. **Create the `registration.php` File:**
    - In the `app/code/LandingPage/Form` directory, create a new file called `registration.php`.

4. **Create the `module.xml` Configuration File:**
    - Inside the `app/code/LandingPage/Form/etc` directory, create a file named `module.xml`.
  
   ### Final Directory Structure

After completing the steps above, your directory structure should look like this:

```plaintext
app
└── code
    └── LandingPage
        └── Form
            ├── etc
            │   └── module.xml
            └── registration.php
```

## Register Module

To register the module, add the following code to `registration.php`:

```php
<?php

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    
     // The name of the module we're registering
    'LandingPage_Form',
    __DIR__
);
```

module.xml File Content


```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="LandingPage_Form" setup_version="1.0.0"/>
</config>
```


## Installation

To install the module, follow these steps:

1. Navigate to the root directory of your Magento 2 installation:
    ```bash
    cd /var/www/magento2
    ```
2. Run the following commands to register the module and update dependencies:

    ```bash
    php bin/magento setup:upgrade
    ```

    ```bash
    php bin/magento setup:di:compile
    ```

3. Generates Static Files

    ```bash
    php bin/magento setup:static-content:deploy -f
    ```

4. Flush the cache:
    ```bash
    php bin/magento cache:flush
    ```

# Display Block
Now, we are creating a landing page.

## Steps to Implement

### 1. Create the `Block` Directory
- Navigate to the following directory in your Magento installation:  
  `app/code/LandingPage/Form`
- Create a new folder named `Block`.

### 2. Create the `Index.php` File
- Within the newly created `Block` directory, create a file named `Index.php`.

### 3. Populate `Index.php` with the Following Code
```php
<?php
namespace LandingPage\Form\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template{
    /**
     * Get catalog display text
     * 
     * @return string
     */
    public function getCatalogDisplayText(){
        return "Block content on index index page";
    }
}
```

### 4. Set Up the `view` Directory Structure

1. Inside the `app/code/LandingPage/Form` directory, create a new directory named `view`.
2. Within the `view` directory, create a subdirectory called `frontend`.
3. Next, create another subdirectory within `frontend` named `templates`.
4. In the `templates` directory, create a file named `content.phtml`.

Add the following static content to the `content.phtml` file:

```php
<?php
<?php $blockContent = $block->getCatalogDisplayText(); ?>
<h1><?= $blockContent ?></h1>
```

### 5. Set Up the `Controller` Directory Structure

1. Inside the `app/code/LandingPage/Form` directory, create a new directory named `Controller`.
2. Within the `Controller` directory, create a subdirectory called `Index`.
3. Within the `Index` directory, create a file named `Index.php`.


Add the following PHP code to the `Index.php` file:

```php
<?php
namespace LandingPage\Form\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action{
    protected $_pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory){
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute(){
        return $this->_pageFactory->create();
    }
}
```

### 6. Set Up the `landingpage_index_index.xml`
1. Inside the `app/code/LandingPage/Form/view/frontend` directory, create a new directory named `layout`.
2. Within the `layout` directory, create a file called `landingpage_index_index.xml`.

Add the following PHP code to the `landingpage_index_index.xml` file:


```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
            <block class="LandingPage\Form\Block\Index" name="form_block" template="LandingPage_Form::content.phtml"/>
        </referenceContainer>
    </body>
</page>
```

### 7. Set Up the `routes.xml`
1. Inside the `app/code/LandingPage/Form/etc/` directory, create a new directory named `frontend`.
2. Within the `layout` directory, create a file called `routes.xml`.

Add the following XML code to the `routes.xml` file:
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
    <router id="standard">
        <route id="landingpage" frontName="landingpage">
            <module name="LandingPage_Form"/>
        </route>
    </router>
</config>
```

## Updating Magento

```bash
php bin/magento s:d:c
```

```bash
php bin/magento c:f
```

## Adding Alpine.js CDN to the Default Head Block

### 7. Create the `default_head_blocks.xml` file
1. Navigate to the following directory within your Magento installation:
  `app/code/LandingPage/Form/view/frontend/layout/`
2. Create a new file named `default_head_blocks.xml` in this directory.

### Update Magento
```bash
bin/magento cache:clean
bin/magento setup:static-content:deploy -f
```

## Adding Tailwind CDN to the Default Head Block

### 7. Create the `default_head_blocks.xml` file
1. Navigate to the following directory within your Magento installation:
  `app/code/LandingPage/Form/view/frontend/layout/`
2. Update `default_head_blocks.xml`

```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <head>
        <script src="https://unpkg.com/alpinejs" src_type="url" defer="true"/>
        <script src="https://cdn.tailwindcss.com" src_type="url" defer="true"></script>
    </head>
</page>
```
