## Executing tests using Docker

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/marydn/basket.git`
3. Move to the project folder: `cd basket`
4. Install PHP dependencies and bring up the project Docker containers with Docker Compose: `make build`
5. Check everything's up using: `$ docker-composer ps`. It should show `php` service up.
6. Execute tests: `make test`

> Note: If you want to bring down Docker service use: `make destroy`

### Executing tests without Docker

1. [Install Composer](https://getcomposer.org/doc/00-intro.md)
2. Check PHP version. Should be 7.4 at least
3. Install dependencies: `composer install`
4. Execute tests: `make run-tests`

### Diving project structure:

```bash
$ tree -L 4 src
src
├── Basket.php # Collection that holds products to check
├── Exception
│   └── ProductNotFount.php
├── Inventory.php # Collection that holds products available at the store
├── Pricing.php # Sets a price by unit or volume
├── Product.php # Represents a Product
├── ProductPrices.php # Collection of prices per Product
├── Shared
│   └── Collection.php # Holds collections of objects
├── Terminal.php # Main objet to interact with basket, inventory and get Total
└── ValueObject
    └── ProductCode.php
```