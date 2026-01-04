# My Olshop - Yii2 E-Commerce Platform

A modern e-commerce web application built with Yii2 Framework featuring complete shopping cart, checkout system, and customer management.

## Features

### Customer Features

- **Product Catalog**: Browse products with search and category filtering
- **Shopping Cart**: Session-based cart supporting both guest and registered users
- **Checkout System**: Dual payment methods (COD & Bank Transfer)
- **Customer Authentication**: Separate authentication system for customers
- **Order Management**: Track order history and delivery status
- **Profile Management**: Update customer information and password

### Admin Features

- **Product Management**: Full CRUD operations with image upload
- **Category Management**: Organize products into categories
- **Tag System**: Many-to-many relationship for product tagging
- **Dashboard**: Overview of products and stock management

## Technology Stack

- **Framework**: Yii2 PHP Framework
- **Frontend**: Bootstrap 5, Font Awesome 6.4
- **Database**: MySQL with InnoDB engine
- **Architecture**: MVC Pattern with ActiveRecord ORM

## Database Schema

### Core Tables

- `category` - Product categories (One-to-Many with products)
- `product` - Product catalog with pricing and stock
- `tag` - Product tags (Many-to-Many via product_tag)
- `product_tag` - Junction table for product-tag relationship

### E-Commerce Tables

- `customer` - Customer accounts with authentication
- `cart` - Shopping cart items (session-based)
- `orders` - Order records with payment tracking
- `order_items` - Order line items with snapshots
- `user` - Admin user accounts

## Installation

### Prerequisites

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Apache/Nginx web server

### Setup Instructions

1. **Clone Repository**

   ```bash
   git clone <repository-url>
   cd olshop
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Configure Database**
   - Copy `config/db.php.example` to `config/db.php`
   - Update database credentials
4. **Import Database Schema**

   ```bash
   mysql -u root -p olshop < database/olshop_setup.sql
   mysql -u root -p olshop < database/user_table.sql
   mysql -u root -p olshop < database/ecommerce_tables.sql
   ```

5. **Configure Web Server**

   - Set document root to `web/` directory
   - Enable URL rewriting

6. **Create Upload Directories**
   ```bash
   mkdir -p web/uploads/products
   mkdir -p web/uploads/payments
   chmod 777 web/uploads/products
   chmod 777 web/uploads/payments
   ```

### Default Credentials

**Admin Access:**

- URL: `/site/login`
- Email: admin@test.com
- Password: admin123

**Customer Access:**

- URL: `/site/customer-login`
- Email: customer@test.com
- Password: admin123

## Project Structure

```
olshop/
├── assets/          Application asset bundles
├── commands/        Console commands
├── config/          Application configurations
├── controllers/     Web controllers
│   ├── CartController.php
│   ├── CategoryController.php
│   ├── CheckoutController.php
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── SiteController.php
│   └── TagController.php
├── database/        SQL schema and migrations
├── models/          Data models and business logic
│   ├── Cart.php
│   ├── Category.php
│   ├── Customer.php
│   ├── CustomerLoginForm.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── ProductTag.php
│   └── Tag.php
├── runtime/         Generated files (cache, logs)
├── views/           View templates
│   ├── cart/        Shopping cart views
│   ├── checkout/    Checkout and order views
│   ├── dashboard/   Admin dashboard
│   ├── layouts/     Layout templates
│   │   ├── main.php     (Admin layout)
│   │   └── shop.php     (Customer layout)
│   ├── product/     Product management
│   └── site/        Public pages
├── web/             Web root directory
│   ├── assets/      Published assets
│   └── uploads/     User uploaded files
└── widgets/         Reusable widgets
```

## Key Features Implementation

### Dual Authentication System

- Admin: Uses `User` model with `Yii::$app->user`
- Customer: Uses `Customer` model with session-based auth

### Shopping Cart

- Session-based cart supporting guest users
- Auto-merge cart on customer login
- Real-time cart count badge (99+ for >99 items)

### Payment Methods

- **COD**: Cash on Delivery
- **Bank Transfer**: With payment proof upload

### Order Processing

1. Cart validation
2. Customer information collection
3. Order creation with unique order number
4. Order items snapshot (price, quantity at time of order)
5. Stock deduction
6. Cart clearing
7. Order confirmation

## Development Notes

### Code Standards

- PSR-4 autoloading
- Yii2 coding standards
- ActiveRecord for database operations
- CSRF protection enabled

### Security Features

- Password hashing (Yii2 Security component)
- CSRF token validation
- SQL injection prevention (prepared statements)
- XSS protection (HTML encoding)
- Session-based authentication

## License

This project is licensed under the MIT License.

## Contact

For support or inquiries:

- Email: info@myolshop.com
- Phone: 08123456789

---

Built with ❤️ using Yii2 Framework
vendor/ contains dependent 3rd-party packages
views/ contains view files for the Web application
web/ contains the entry script and Web resources

## REQUIREMENTS

The minimum requirement by this project template that your Web server supports PHP 7.4.

## INSTALLATION

### Install via Composer

If you do not have [Composer](https://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](https://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

```
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
```

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

```
http://localhost/basic/web/
```

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](https://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

```
http://localhost/basic/web/
```

### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist

Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install

Start the container

    docker-compose up -d

You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:**

- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches

## CONFIGURATION

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**

- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.

## TESTING

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser.

### Running acceptance tests

To execute acceptance tests do the following:

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer

   ```
   composer update
   ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

   ```
   java -jar ~/selenium-server-standalone-x.xx.x.jar
   ```

   In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

   ```
   # for Firefox
   java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar

   # for Google Chrome
   java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
   ```

   As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:

   ```
   docker run --net=host selenium/standalone-firefox:2.53.0
   ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.

6. Start web server:

   ```
   tests/bin/yii serve
   ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
