# Company Order Metadata Plugin

Custom WordPress plugin that generates a unique internal reference code (`CMP-{ORDER_ID}-{YYYY}`) for every new WooCommerce order.

## Architecture
The plugin follows a **Domain-Driven Design** approach with a clear separation of concerns:
* **`OrderGenerator`**: Handles the business logic (creating and saving the code).
* **`AdminDisplay`**: Handles the presentation logic (displaying it in WP Admin).
* **`Plugin`**: Main entry point that handles initialization.

It uses **PHP Namespaces** to avoid collisions and **Composer** for autoloading classes and managing development dependencies like PHPUnit.

## Hooks Chosen
* `woocommerce_checkout_order_processed`: Chosen because it fires after payment processing logic but before the user sees the Thank You page, ensuring the ID is available and the order is valid.
* `woocommerce_admin_order_data_after_order_details`: Used to inject the read-only field directly into the main order details panel, making it highly visible to store managers without cluttering the meta boxes.

## Testing Strategy
For production, I recommend:
1.  **Unit Tests:** Run `composer test` to verify the ID generation logic format.
2.  **Integration Tests:** Set up a staging environment. create a dummy order using Storefront theme, and verify the meta key `_company_reference_code` exists in the database.
3.  **Filter Check:** Create a small snippet to hook into `company_order_reference_code` to verify extensibility.

## Performance Awareness (High-Volume Stores)
This plugin is lightweight and optimized for high concurrency:
* **Single Write:** It checks `get_meta` before writing to ensure it never runs twice for the same order, preventing race conditions or duplicate writes.
* **HPOS Compatibility:** It uses the CRUD methods (`$order->get_meta`, `$order->save`) instead of direct SQL or `get_post_meta`. This ensures complete compatibility with **WooCommerce High Performance Order Storage (HPOS)**, which is crucial for high-volume stores to avoid locking the `wp_postmeta` table.

## Installation
1. Run `composer install` to generate the autoloader.
2. Activate the plugin in WordPress.