# Company Order Metadata Plugin

Hola. Esta es mi solución para la prueba técnica de WordPress/WooCommerce.

El objetivo del plugin es generar un código de referencia interno (`CMP-{ID}-{YYYY}`) automáticamente para cada nuevo pedido y mostrarlo en el admin de forma segura.

## 🏗 Arquitectura y Estructura

He decidido separar la lógica en clases pequeñas y específicas siguiendo el principio de responsabilidad única (SRP), en lugar de meter todo en un solo archivo gigante:

* **`OrderGenerator`**: Se encarga puramente de la lógica de negocio (generar el código, validar duplicados y guardar).
* **`AdminDisplay`**: Se encarga solo de la interfaz visual (pintar la Meta Box en el admin).
* **Namespaces**: Usé `Company\OrderMetadata` para evitar cualquier conflicto con otros plugins, incluso si tienen nombres de clases similares.

## ⚓ Selección de Hooks

Esta fue la parte crítica para asegurar la estabilidad:

1.  **`woocommerce_new_order`**:
    * *¿Por qué este?* Inicialmente consideré `save_post` o hooks de checkout, pero esos pueden dispararse múltiples veces o antes de que el ID del pedido esté listo. `woocommerce_new_order` es el estándar moderno; se dispara una sola vez justo después de que la orden se crea, garantizando que ya tengo un `ORDER_ID` válido para cumplir con el formato requerido.

2.  **`add_meta_boxes`**:
    * *¿Por qué este?* Para la UI, preferí usar una Meta Box nativa de WordPress en la barra lateral (`side`). Es menos intrusiva que inyectar HTML arbitrario en medio de los detalles del pedido y se siente más integrada en la interfaz de WooCommerce.

## 🚀 Rendimiento y Alto Volumen

Pensando en tiendas con miles de transacciones, optimicé el código así:

* **Compatibilidad HPOS (High-Performance Order Storage):** No utilicé funciones directas de WordPress como `get_post_meta` o `update_post_meta`. En su lugar, usé los métodos CRUD de WooCommerce (`$order->get_meta()`, `$order->save_meta_data()`). Esto asegura que el plugin seguirá funcionando si la tienda migra sus tablas de pedidos a la nueva estructura optimizada de WooCommerce.
* **Lectura antes de Escritura:** El código siempre verifica si el meta ya existe antes de intentar generarlo. Esto previene escrituras innecesarias en la base de datos y evita condiciones de carrera (race conditions).

## ✅ Testing

### 1. Pruebas Unitarias
El repositorio incluye tests con **PHPUnit**. He mockeado el objeto `WC_Order` para probar la lógica de generación sin necesitar una base de datos activa.
```bash
composer install
./vendor/bin/phpunit tests/OrderGeneratorTest.php