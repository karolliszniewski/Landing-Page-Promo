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

### 8. Create the `default_head_blocks.xml` file
1. Navigate to the following directory within your Magento installation:
  `app/code/LandingPage/Form/view/frontend/layout/`
2. Create a new file named `default_head_blocks.xml` in this directory.

```xml
<?xml version="1.0"?> 
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <head>
        <script src="https://unpkg.com/alpinejs" src_type="url" defer="true"/>
        <script src="https://cdn.tailwindcss.com" src_type="url" defer="true"></script> 
    </head>
</page>
```

### Update Magento
```bash
php bin/magento cache:clean
php bin/magento setup:static-content:deploy -f
```

## Read User Sessions data

We will modify the file `app/code/LandingPage/Form/Block/Index.php`

In this step, I had to use ObjectManagerInterface to retrieve the CustomerSession because other dependency injection methods didn’t work in this case. While using ObjectManager is not recommended in Magento 2 due to its negative impact on performance and testability, this is a temporary workaround until I find a better approach. Normally, ObjectManager should only be used in specific situations such as factories.

```php
$this->customerSession = $objectManager->get(CustomerSession::class);
```

We make the $customerSession property protected because it will only be used within this class. Then, we can create methods to fetch customer data, such as the first name and email.

```php
    public function getCustomerName(): string
    {
        $customer = $this->customerSession->getCustomer();
        return $customer->getFirstname() ?: '';
    }
```

the full code looks like this now 

```php
<?php
namespace LandingPage\Form\Block;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class Index extends Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * Constructor.
     *
     * @param Template\Context $context
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->customerSession = $objectManager->get(CustomerSession::class);
        parent::__construct($context, $data);
    }

    /**
     * Get the customer's first name.
     *
     * @return string
     */
    public function getCustomerName(): string
    {
        $customer = $this->customerSession->getCustomer();
        return $customer->getFirstname() ?: '';
    }

    public function getCustomerEmail(): string
    {
        $customer = $this->customerSession->getCustomer();
        return $customer->getEmail() ?: '';
    }
}
```


And `app/code/LandingPage/Form/view/frontend/templates/content.phtml`

```phtml 
<!-- app/code/LandingPage/Form/view/frontend/templates/landing_form.phtml -->

<?php
/** @var LandingPage\Form\Block\Index $block */
?>
<div class="landing-page-content">
    <?php
    if ($customerName = $block->getCustomerName()) {
        $customerEmail = $block->getCustomerEmail();
    }
    ?>

    <?php if ($customerName) { ?>

        <fieldset class="fieldset">
            <legend class="legend"><span>Contact Information</span></legend>
            <br>

            <input type="hidden" name="error_url" value="">

            <div class="field required">
                <label class="label" for="name">
                    <span>Name</span>
                </label>
                <div class="control">
                    <input type="text" id="name" name="name" value="<?= $block->escapeHtml($customerName) ?>"
                        title="Name" class="input-text required-entry" data-validate="{required:true}" aria-required="true" disabled>
                </div>
            </div>

            <div class="field required">
                <label for="email" class="label">
                    <span>Email</span>
                </label>
                <div class="control">
                    <input type="email" name="email" id="email" value="<?= $block->escapeHtml($customerEmail) ?>"
                        title="Email" class="input-text" disabled>
                </div>
            </div>

            <div class="field">
                <label for="telephone" class="label">
                    <span>Comment</span>
                </label>
                <div class="control">
                    <textarea type="tel" name="telephone" id="telephone" class="input-textarea"></textarea>
                </div>
            </div>

            <div style="margin-bottom:.5rem;">
                <button type="submit" title="Add to Cart" class="action tocart primary">
                    <span>Add to Cart</span>
                </button>
            </div>

            <div>
                <button style="background-color: #00B05A;" type="submit" title="Read More" class="action tocart primary">
                    <span>Read More</span>
                </button>
            </div>
        </fieldset>

    <?php } else { ?>

        <h1 style="font-size:5rem;">Sign In to Continue</h1>

    <?php } ?>
</div>

```


File structure:
```bash
/var/www/html/app/code
└── LandingPage
    └── Form
        ├── Block
        │   └── Index.php
        ├── Controller
        │   └── Index
        │       └── Index.php
        ├── etc
        │   ├── frontend
        │   │   └── routes.xml
        │   ├── layout
        │   └── module.xml
        ├── registration.php
        └── view
            └── frontend
                ├── layout
                │   ├── default_head_blocks.xml
                │   └── landingpage_index_index.xml
                └── templates
                    └── content.phtml
```


### Create the Admin Page


### 1. Define an admin controller
1. Inside the `LandingPage/Form/Controller/Adminhtml/Index` directory, create a new file named `Index.php`.
2. Within the `Index` directory, create a file called `Index.php`.

```bash
LandingPage/Form/Controller/Adminhtml
└── Index
    └── Index.php
```

3. Content of Index.php:

```php
<?php

namespace LandingPage\Form\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Landing Page Admin'));
        return $resultPage;
    }
}
```

### 2. Define an admin route
1. Inside the `LandingPage/Form/etc/adminhtml/` directory, create a new file named `routes.xml`.
2. Content of routes.xml:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">
    <router id="admin">
        <route id="landingpage" frontName="landingpage">
            <module name="LandingPage_Form" before="Magento_Backend"/>
        </route>
    </router>
</config>
```

### 3. Define the menu entry
1. Inside the `LandingPage/Form/etc/adminhtml/` directory, create a new file named `menu.xml`.
2. Content of menu.xml:

```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:framework:Config/etc/menu.xsd">
    <menu>
        <add id="LandingPage_Form::landingpage"
            title="Landing Page"
            module="LandingPage_Form"
            sortOrder="100"
            parent="Magento_Backend::content"
            action="landingpage/index/index"
            resource="LandingPage_Form::landingpage" />
    </menu>
</config>
```

### 3. Define the Admin ACL (Access Control List)
1. Inside the `LandingPage/Form/etc` directory, create a new file named `acl.xml`.
2. Content of acl.xml:

```xml
<?xml version="1.0"?>
<acl xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:framework:Acl/etc/acl.xsd">
    <resources>
        <resource id="Magento_Backend::admin">
            <resource id="LandingPage_Form::landingpage" title="Landing Page Admin" sortOrder="100" />
        </resource>
    </resources>
</acl>
```

### 3. Define the Admin Layout File
1. Inside the `LandingPage/Form/view/adminhtml/layout` directory, create a new file named `landingpage_index_index.xml`.
2. Content of landingpage_index_index.xml:

```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
            <block class="Magento\Backend\Block\Template" template="LandingPage_Form::admin/landingpage.phtml"/>
        </referenceContainer>
    </body>
</page>
```