# Documentación de Endpoints

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
  "history": []
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
