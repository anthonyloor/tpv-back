# Documentación de Endpoints
## Índice
- [Productos](#productos)
- [Control de stock](#control-de-stock)
- [Autenticación](#autenticación)
- [Configuración TPV](#configuración-tpv)
- [Clientes](#clientes)
- [Pedidos](#pedidos)
- [Sesiones TPV](#sesiones-tpv)
- [Empleados](#empleados)
- [Tiendas](#tiendas)
- [Reglas de carrito](#reglas-de-carrito)
- [Licencias](#licencias)
- [Movimientos de almacén](#movimientos-de-almacén)

## Productos

## `/product_search`

**Método:** `POST`

Busca un producto por referencia o código EAN13. Se debe enviar el término de búsqueda y el grupo por defecto del usuario.

### Solicitud de ejemplo

```json
{
  "search_term": "1234567890123",
  "id_default_group": 1
}
```

### Respuesta de ejemplo

```json
[
  {
    "id_product": 10,
    "id_product_attribute": 0,
    "id_stock_available": 55,
    "id_shop": 1,
    "product_name": "Zapatilla deportiva",
    "combination_name": null,
    "reference_combination": "ZAP-001",
    "name_category": "Deporte",
    "id_category": 4,
    "ean13_combination": "1234567890123",
    "ean13_combination_0": null,
    "price": 24.2,
    "quantity": 16,
    "shop_name": "Tienda Principal",
    "link_rewrite": "zapatilla-deportiva",
    "impact_price": null
  }
]
```

---
## Control de stock

## `/get_controll_stock_filtered`

**Método:** `POST`

Devuelve los controles de stock asociados a un código EAN13.

### Solicitud de ejemplo

```json
{
  "ean13": "1234567890123"
}
```

### Respuesta de ejemplo

```json
[
  {
    "id_control_stock": 12,
    "id_product": 10,
    "id_product_attribute": 0,
    "id_shop": 1,
    "date_add": "2024-05-01 10:23:00",
    "ean13": "1234567890123",
    "active": true,
    "printed": false,
    "product_name": "Zapatilla deportiva"
  }
]
```

---

## `/get_controll_stocks`

**Método:** `GET`

Devuelve los últimos 200 controles de stock registrados.

### Respuesta de ejemplo

```json
[
  {
    "id_control_stock": 12,
    "id_product": 10,
    "id_product_attribute": 0,
    "id_shop": 1,
    "date_add": "2024-05-01 10:23:00",
    "ean13": "1234567890123",
    "active": true,
    "printed": false,
    "product_name": "Zapatilla deportiva"
  }
]
```

---

## `/get_controll_stock`

**Método:** `GET`

Obtiene la información completa de un control de stock.

### Solicitud de ejemplo

```http
GET /get_controll_stock?id=12
```

### Respuesta de ejemplo

```json
{
  "id_control_stock": 12,
  "id_product": 10,
  "id_product_attribute": 0,
  "id_shop": 1,
  "date_add": "2024-05-01 10:23:00",
  "ean13": "1234567890123",
  "active": true,
  "printed": false,
  "product_name": "Zapatilla deportiva",
  "history": [
    {
      "id_control_stock_history": 5,
      "id_control_stock": 12,
      "id_shop": 1,
      "reason": "Creacion",
      "type": "IN",
      "date": "2024-05-01 10:23:00",
      "id_transaction_detail": null
    }
  ]
}
```

---

## `/get_product_price_tag`

**Método:** `POST`

Genera etiquetas de precio para un producto o devuelve una etiqueta existente.

### Solicitud de ejemplo

```json
{
  "id_product": 10,
  "id_product_attribute": 0,
  "id_shop": 1,
  "ean13": "1234567890123",
  "quantity": 20,
  "quantity_print": 1,
  "product_name": "Zapatilla deportiva"
}
```

### Respuesta de ejemplo

```json
{
  "tags": [
    {
      "id_control_stock": 15,
      "id_product": 10,
      "id_product_attribute": 0,
      "id_shop": 1,
      "date_add": "2024-05-03 09:15:00",
      "ean13": "1234567890123",
      "active": true,
      "printed": true
    }
  ]
}
```

---

## `/get_stock_report`

**Método:** `POST`

Obtiene un informe de stock filtrado por término de búsqueda.

### Solicitud de ejemplo

```json
{
  "license": "ABC123",
  "search_term": "zapatilla",
  "value": "reference",
  "id_shop": 1
}
```

### Respuesta de ejemplo

```json
[
  {
    "id_product": 10,
    "id_product_attribute": 0,
    "id_stock_available": 55,
    "id_shop": 1,
    "product_name": "Zapatilla deportiva",
    "combination_name": null,
    "reference_combination": "ZAP-001",
    "name_category": "Deporte",
    "ean13_combination": "1234567890123",
    "ean13_combination_0": null,
    "price": 29.28,
    "quantity": 16
  }
]
```

---

## `/generate_ean13`

**Método:** `POST`

Genera códigos EAN13 para los productos indicados.

### Solicitud de ejemplo

```json
{
  "products": [
    {"id_product": 10, "id_product_attribute": 0}
  ]
}
```

### Respuesta de ejemplo

```json
{
  "ean13": [
    {"ean13": "1234567890123", "id_product": 10, "id_product_attribute": 0}
  ]
}
```

---
## Autenticación

## `/login`

**Método:** `POST`

Autentica a un empleado y devuelve un token JWT.

### Solicitud de ejemplo

```json
{
  "id_employee": 1,
  "password": "secret"
}
```

### Respuesta de ejemplo

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---
## Configuración TPV

## `/get_config_tpv`

**Método:** `GET`

Recupera la configuración del TPV asociada a una licencia.

### Solicitud de ejemplo

```http
GET /get_config_tpv?license=ABC123
```

### Respuesta de ejemplo

```json
{
  "license": "ABC123",
  "id_customer_default": 2,
  "id_address_delivery_default": 3,
  "allow_out_of_stock_sales": false,
  "ticket_text_header_1": "Bienvenido",
  "ticket_text_header_2": "Gracias por su compra",
  "ticket_text_footer_1": "Hasta pronto",
  "ticket_text_footer_2": "Vuelva pronto"
}
```

---

## `/create_config_tpv`

**Método:** `POST`

Crea una nueva configuración del TPV.

### Solicitud de ejemplo

```json
{
  "license": "ABC123",
  "id_customer_default": 2,
  "id_address_delivery_default": 3
}
```

### Respuesta de ejemplo

```json
{
  "status": "success",
  "message": "TPV Config created successfully"
}
```

---

## `/update_tpv_config`

**Método:** `POST`

Actualiza la configuración del TPV existente.

### Solicitud de ejemplo

```json
{
  "license": "ABC123",
  "allow_out_of_stock_sales": true
}
```

### Respuesta de ejemplo

```json
{
  "status": "success",
  "message": "TPV Config updated successfully"
}
```

---

## `/get_pin`

**Método:** `POST`

Solicita la generación de un pin temporal para administradores.

### Solicitud de ejemplo

```json
{
  "id_employee_request": 1
}
```

### Respuesta de ejemplo

```json
{
  "id_pin": 5,
  "pin": "1234",
  "date_add": "2024-05-04 10:00:00",
  "active": true
}
```

---

## `/check_pin`

**Método:** `POST`

Verifica un pin previamente generado.

### Solicitud de ejemplo

```json
{
  "pin": "1234",
  "id_employee_used": 2,
  "reason": "Cambio de precio"
}
```

### Respuesta de ejemplo

```json
{
  "usable": true
}
```

---
## Empleados

## `/employees`

**Método:** `GET`

Obtiene la lista de empleados activos.

### Respuesta de ejemplo

```json
[
  {
    "id_employee": 1,
    "id_profile": 1,
    "employee_name": "John Doe"
  }
]
```

---
## Tiendas

## `/shops`

**Método:** `GET`

Devuelve las tiendas disponibles.

### Respuesta de ejemplo

```json
[
  {
    "id_shop": 1,
    "name": "Tienda Principal",
    "virtual_uri": "shop"
  }
]
```

---
## Sesiones TPV

## `/open_pos_session`

**Método:** `POST`

Abre una nueva sesión de punto de venta.

### Solicitud de ejemplo

```json
{
  "id_shop": 1,
  "id_employee": 1,
  "init_cash": 100,
  "license": "ABC123"
}
```

### Respuesta de ejemplo

```json
{
  "status": "OK",
  "message": "Point Of Sale Session created"
}
```

---

## `/check_pos_session`

**Método:** `GET`

Comprueba si existe una sesión activa para la licencia indicada.

### Solicitud de ejemplo

```http
GET /check_pos_session?license=ABC123
```

### Respuesta de ejemplo

```json
{
  "status": "OK",
  "opening_date": "2024-05-04 09:00:00"
}
```

---

## `/close_pos_session`

**Método:** `POST`

Cierra la sesión de punto de venta activa.

### Solicitud de ejemplo

```json
{
  "license": "ABC123",
  "id_employee": 1
}
```

### Respuesta de ejemplo

```json
{
  "status": "OK",
  "message": "Point Of Sale Session updated"
}
```

---

## `/get_report_amounts`

**Método:** `GET`

Devuelve el total de cobros de la sesión activa.

### Solicitud de ejemplo

```http
GET /get_report_amounts?license=ABC123
```

### Respuesta de ejemplo

```json
{
  "status": "OK",
  "total_cash": 150.0,
  "total_card": 200.0,
  "total_bizum": 50.0,
  "date_close": "2024-05-04 18:00:00",
  "date_add": "2024-05-04 09:00:00",
  "id_employee_open": 1,
  "id_employee_close": 2
}
```

---

## `/get_pos_sessions`

**Método:** `GET`

Lista todas las sesiones de punto de venta registradas.

### Respuesta de ejemplo

```json
[
  {
    "id_pos_session": 1,
    "id_shop": 1,
    "id_employee_open": 1,
    "id_employee_close": 2,
    "date_add": "2024-05-03 09:00:00",
    "date_close": "2024-05-03 18:00:00",
    "init_cash": 100,
    "total_cash": 150.0,
    "total_card": 200.0,
    "total_bizum": 50.0,
    "active": false,
    "license": "ABC123"
  }
]
```

## Reglas de carrito

### `/get_cart_rule`

**Método:** `GET`

Devuelve la información de un vale descuento a partir de su código. Ahora también incluye las restricciones de tiendas asociadas.

### Solicitud de ejemplo
```http
GET /get_cart_rule?code=SUMMER24
```

### Respuesta de ejemplo
```json
{
  "code": "SUMMER24",
  "reduction_amount": 5.0,
  "reduction_percent": 0,
  "active": true,
  "restrictions": {
    "id_shop": "1,2"
  }
}
```

### `/get_cart_rules`

**Método:** `GET`

Obtiene una lista de los últimos vales generados. Opcionalmente se puede filtrar por rango de fechas.

### Solicitud de ejemplo
```json
{
  "date1": "2024-05-01",
  "date2": "2024-05-31"
}
```

### Respuesta de ejemplo
```json
[
  {
    "code": "SPRING24",
    "reduction_amount": 10.0,
    "active": true
  }
]
```

### `/create_cart_rule`

**Método:** `POST`

Crea un nuevo vale descuento.

### Solicitud de ejemplo
```json
{
  "date_from": "2024-05-01",
  "date_to": "2024-06-01",
  "description": "Promoción de mayo",
  "name": "MAYO",
  "quantity": 1,
  "reduction_amount": 5.0,
  "reduction_percent": 0,
  "id_customer": 1
}
```

### Respuesta de ejemplo
```json
[
  {
    "code": "MAYO24",
    "reduction_amount": 5.0,
    "active": true
  }
]
```

## Licencias

### `/license_check`

**Método:** `POST`

Activa o comprueba el estado de una licencia para una tienda determinada.

### Solicitud de ejemplo
```json
{
  "id_shop": 1,
  "license": "ABC123"
}
```

### Respuesta de ejemplo
```json
{
  "status": "OK",
  "message": "License actived"
}
```

## Clientes

### `/get_customers_filtered`

**Método:** `POST`

Filtra clientes por identificador o por nombre/teléfono.

### Solicitud de ejemplo
```json
{
  "origin": "all",
  "filter": "Pérez"
}
```

### Respuesta de ejemplo
```json
[
  {
    "id_customer": 5,
    "firstname": "Luis",
    "lastname": "Pérez",
    "email": "luis@example.com",
    "phone": "600123123",
    "origin": "mayret"
  }
]
```

### `/get_all_customers`

**Método:** `GET`

Devuelve los últimos clientes registrados de todas las tiendas.

### Respuesta de ejemplo
```json
[
  { "id_customer": 5, "firstname": "Luis", "lastname": "Pérez" }
]
```

### `/get_addresses`

**Método:** `POST`

Obtiene las direcciones asociadas a un cliente.

### Solicitud de ejemplo
```json
{
  "id_customer": 5,
  "origin": "mayret"
}
```

### Respuesta de ejemplo
```json
[
  {
    "id_address": 20,
    "city": "Madrid",
    "address1": "Calle Falsa 123"
  }
]
```

### `/get_groups`

**Método:** `GET`

Lista los grupos de clientes disponibles.

### Respuesta de ejemplo
```json
[
  { "id_group": 1, "name": "Default" }
]
```

### `/create_customer`

**Método:** `POST`

Crea un nuevo cliente.

### Solicitud de ejemplo
```json
{
  "firstname": "Ana",
  "lastname": "López"
}
```

### Respuesta de ejemplo
```json
{
  "message": "Customer created successfully",
  "id_customer": 10
}
```

### `/create_address`

**Método:** `POST`

Añade una dirección a un cliente existente.

### Solicitud de ejemplo
```json
{
  "id_customer": 10,
  "id_country": 1,
  "id_state": 1,
  "alias": "Principal",
  "lastname": "López",
  "firstname": "Ana",
  "address1": "Avenida 1",
  "postcode": "28001",
  "city": "Madrid"
}
```

### Respuesta de ejemplo
```json
{
  "message": "Address created successfully",
  "id_address": 30
}
```

### `/edit_customer`

**Método:** `POST`

Modifica los datos de un cliente existente.

### Solicitud de ejemplo
```json
{
  "id_customer": 10,
  "firstname": "Ana",
  "lastname": "López García"
}
```

### Respuesta de ejemplo
```json
{
  "message": "Customer updated successfully"
}
```

### `/edit_address`

**Método:** `POST`

Actualiza una dirección existente.

### Solicitud de ejemplo
```json
{
  "id_address": 30,
  "id_customer": 10,
  "id_country": 1,
  "id_state": 1,
  "alias": "Principal",
  "lastname": "López",
  "firstname": "Ana",
  "address1": "Avenida 1",
  "postcode": "28001",
  "city": "Madrid"
}
```

### Respuesta de ejemplo
```json
{
  "message": "Address updated successfully"
}
```

## Pedidos

### `/create_order`

**Método:** `POST`

Genera un nuevo pedido y actualiza la sesión TPV correspondiente.

### Solicitud de ejemplo
```json
{
  "id_shop": 1,
  "id_customer": 5,
  "id_address_delivery": 20,
  "payment": "cash",
  "total_cash": 50,
  "total_card": 0,
  "total_bizum": 0,
  "total_paid": 50,
  "total_paid_tax_excl": 41.32,
  "total_products": 2,
  "total_discounts": 0,
  "total_discounts_tax_excl": 0,
  "order_details": [
    {"id_product": 1, "product_quantity": 2}
  ],
  "license": "ABC123",
  "id_employee": 1
}
```

### Respuesta de ejemplo
```json
{
  "status": "OK",
  "message": "Order created with id 100"
}
```

### `/get_order`

**Método:** `POST`

Devuelve la información completa de un pedido existente.

### Solicitud de ejemplo
```json
{
  "id_order": 100,
  "origin": "mayret"
}
```

### Respuesta de ejemplo
```json
{
  "id_order": 100,
  "id_shop": 1,
  "payment": "cash",
  "total_paid": 50,
  "order_details": []
}
```

### `/get_shop_orders`

**Método:** `POST`

Lista los pedidos asociados a una tienda.

### Solicitud de ejemplo
```json
{
  "id_shop": 1
}
```

### Respuesta de ejemplo
```json
[
  { "id_order": 100, "total_paid": 50 }
]
```

### `/get_last_orders_by_customer`

**Método:** `POST`

Obtiene las últimas órdenes de un cliente.

### Solicitud de ejemplo
```json
{
  "id_customer": 5,
  "origin": "mayret"
}
```

### Respuesta de ejemplo
```json
[
  { "id_order": 100, "total_paid": 50 }
]
```

### `/get_sale_report_orders`

**Método:** `POST`

Genera un informe de ventas entre fechas para las licencias indicadas.

### Solicitud de ejemplo
```json
{
  "licenses": ["ABC123"],
  "date1": "2024-05-01",
  "date2": "2024-05-31"
}
```

### Respuesta de ejemplo
```json
[
  { "id_order": 100, "total_paid": 50 }
]
```

### `/update_online_orders`

**Método:** `POST`

Registra las ventas realizadas a través de la tienda online y actualiza el estado del pedido.

### Solicitud de ejemplo
```json
{
  "id_order": 200,
  "status": 3,
  "origin": "mayret",
  "shops": []
}
```

### Respuesta de ejemplo
```json
{
  "status": "OK",
  "message": "ORDER_UPDATED200"
}
```

### `/get_pos_session_sale_report_orders`

**Método:** `POST`

Devuelve las ventas asociadas a una sesión concreta de TPV.

### Solicitud de ejemplo
```json
{
  "id_pos_session": 1
}
```

### Respuesta de ejemplo
```json
[
  { "id_order": 100, "total_paid": 50 }
]
```

## Movimientos de almacén

### `/get_warehouse_movements`

**Método:** `POST`

Lista los movimientos de almacén registrados, pudiendo filtrar por fechas.

### Solicitud de ejemplo
```json
{
  "data1": "2024-05-01",
  "data2": "2024-05-31"
}
```

### Respuesta de ejemplo
```json
[
  {
    "id_warehouse_movement": 3,
    "description": "Envío entre tiendas",
    "status": "Ejecutado"
  }
]
```

### `/get_warehouse_movement`

**Método:** `GET`

Obtiene la información detallada de un movimiento de almacén.

### Solicitud de ejemplo
```http
GET /get_warehouse_movement?id_warehouse_movement=3
```

### Respuesta de ejemplo
```json
{
  "id_warehouse_movement": 3,
  "description": "Envío entre tiendas",
  "movement_details": []
}
```

### `/create_warehouse_movement`

**Método:** `POST`

Crea un movimiento de almacén con sus detalles.

### Solicitud de ejemplo
```json
{
  "description": "Traslado de stock",
  "type": "salida",
  "id_employee": 1,
  "movements_details": []
}
```

### Respuesta de ejemplo
```json
{
  "id_warehouse_movement": 4,
  "description": "Traslado de stock",
  "total_quantity": 0
}
```

### `/update_warehouse_movement`

**Método:** `POST`

Modifica los datos de un movimiento de almacén existente.

### Solicitud de ejemplo
```json
{
  "id_warehouse_movement": 4,
  "status": "En camino"
}
```

### Respuesta de ejemplo
```json
{
  "id_warehouse_movement": 4,
  "status": "En camino"
}
```

### `/execute_warehouse_movement`

**Método:** `POST`

Ejecuta un movimiento pendiente y genera los controles de stock asociados.

### Solicitud de ejemplo
```json
{
  "id_warehouse_movement": 4
}
```

### Respuesta de ejemplo
```json
{
  "movement": { "id_warehouse_movement": 4 },
  "control_stocks": []
}
```

### `/delete_warehouse_movement`

**Método:** `POST`

Elimina un movimiento de almacén y sus detalles.

### Solicitud de ejemplo
```json
{
  "id_warehouse_movement": 4
}
```

### Respuesta de ejemplo
```json
{
  "message": "Movement deleted"
}
```

### `/delete_warehouse_movement_detail`

**Método:** `POST`

Elimina una línea concreta del movimiento.

### Solicitud de ejemplo
```json
{
  "id_warehouse_movement_detail": 10
}
```

### Respuesta de ejemplo
```json
{
  "message": "Detail deleted"
}
```

### `/get_transaction_origin`

**Método:** `POST`

Devuelve el identificador de la transacción asociada a un detalle.

### Solicitud de ejemplo
```json
{
  "type": "order",
  "id_transaction_detail": 10
}
```

### Respuesta de ejemplo
```json
{
  "id_order": 5
}
```
