**Considering the project is getting some few interest, I'll work on improving it.**

# About EFLInventory-V2
EFLInventory V2 is a simple-to-use, web-based inventory and point-of-sale application written in PHP using Laravel framework. It uses MySQL as the data backend and has a Bootstrap 4 material-like user interface. It is designed to assist small-scale retail stores with keeping track of items and inventory, and generate reports based on inventory, purchases and sales.

## Well, What Can I Do With It?
- Manage products and product variations
- Manage product categories and sub-categories
- Stock products by batch. Enables proper handling of product expiry date, product price change, e.t.c.
- Record damaged products.
- Generate a range of purchase and sales reports
- Easily migrate from existing records (using a predefined Excel template)
- Track and record major user actions such as create, update and delete. When you have more than 1 staff selling and/or managing products, you might one day want to know who did what.
- Assign roles to users. Default available roles are "Manager" and "Employee". No much option for now. Future updates will allow custom roles and defining actions for such roles. You can also make a request if you really do need one and can't fix it on your own
- Stock and sell products using a barcode scanner
- Sell products from point-of-sale (POS) with good-looking UI and great options
- Print sales receipt for every POS sale

## Screenshots
[Home Screen](screenshots/Home.png)
![](screenshots/Home.png?raw=true )

[POS Screen](screenshots/POS.png)
![](screenshots/POS.png?raw=true)

[Migrate Screen](screenshots/Migrate.png) (Migrate from previous records using a predefined MS Excel template)
![](screenshots/Migrate.png)

[SEE MORE](meta/readme.md)

## Setup
1. Easiest way to get started with this:
```shell
git clone https://github.com/chrisidakwo/eflinventory-v2.git
cd eflinventory-v2
```

2. Run from command prompt
```shell
composer install
```

3. Copy `.env.example` to `.env`. Update details to suit your server & DB setup.

4. Generate application key using: 
``
php artisan key:generate
``

5. To create database tables either run these two commands:
```shell
php artisan migrate

php artisan migrate:seed
```

or run the `database.sql` script.

If you're gonna be running the SQL script, please ensure to look through it before running. It's not a harmful script, but just be sure.
The `database.sql` file is located within the `bootstrap` directory. In there you'll also find a `config.app.php` file. Rename it to `app.php`, update the necessary details and move it to the `config` directory
<p>
Manager login details:
<br>Username: chrisidakwo
<br>Password: secret 
</p>

## User Interface
[Material Pro Admin Template](https://themeforest.net/item/materialpro-bootstrap-4-admin-template/20203944) is the UI theme used for this application. 

## Known Issues (as of 31st Dec 2017)
- `js.printArea` plugin duplicates the receipt for printing.
- Unhandled exceptions for migration using Excel spreadsheets.
- JS click function is not triggered on filtered products in POS (making it difficult to use search bar or barcode scanner).

## TODO
- Code refactoring, tests, and bug fixing.
- Enhance sales receipt customisation. Businesses should be able to add logo, specify how the date and timestamp should appear, choose font, decide to display sales clerk's name, e.t.c.
- An option for frequent database backup (most likely as a cron job).
- Restructure app to allow for custom RBAC.
- Card online payment (Paystack, Stripe and/or any other).
- Provide better report generation options (with charts and graphs)

## License
EFLInventory-V2 is an open-source software licensed under the [GPU v3 License](https://www.google.com.ng/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwin57Oi5szYAhULBcAKHS0RAQ8QFggnMAA&url=https%3A%2F%2Fwww.gnu.org%2Flicenses%2Fgpl-3.0.en.html).
