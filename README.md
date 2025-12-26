# Company Order Metadata Plugin

Here is my submission for the WordPress/WooCommerce technical test.

The goal of this plugin is to automatically generate a unique internal reference code (`CMP-{ID}-{YYYY}`) for every new order and display it securely in the admin area.

## 🏗 Architecture & Design Decisions

I opted for a modular approach following the **Single Responsibility Principle (SRP)**, rather than dumping everything into a single file:

* **`OrderGenerator`**: Handles strictly business logic (generating the code, validating uniqueness, and saving).
* **`AdminDisplay`**: Handles strictly the UI (rendering the Meta Box).
* **Namespaces**: I used `Company\OrderMetadata` to prevent collisions with other plugins or themes.

## ⚓ Hook Selection Strategy (The "Why")

Choosing the right hooks was critical to ensure stability and compatibility:

1.  **`woocommerce_new_order`** (Business Logic):
    * *Why?* I initially considered checkout hooks or `save_post`, but those can be unreliable (firing multiple times) or risky (causing transaction failures during checkout). `woocommerce_new_order` is the modern standard; it fires once, immediately after the order is created, ensuring a valid `ORDER_ID` is available for the required format.

2.  **`add_meta_boxes`** (UI):
    * *Why?* For the Admin UI, I chose a native WordPress Meta Box in the sidebar (`side`). It feels more integrated and professional than injecting arbitrary HTML into the order details hooks, and it keeps the main data view clean.

## 🚀 Performance & Scalability (High Volume)

Designing for stores with high transaction volumes:

* **HPOS Compatibility (High-Performance Order Storage):** I avoided direct DB calls like `get_post_meta`. Instead, I used WooCommerce CRUD methods (`$order->get_meta()`, `$order->save_meta_data()`). This ensures the plugin remains compatible even if the store migrates to the new high-performance custom tables.
* **Idempotency:** The code explicitly checks if the metadata already exists before generating it. This prevents duplicate writes and protects against race conditions.

## ✅ Testing Strategy

### 1. Automated Unit Tests
The repository includes tests using **PHPUnit**. I mocked the `WC_Order` object to verify the logic without needing a live database.
```bash
composer install
./vendor/bin/phpunit tests/OrderGeneratorTest.php