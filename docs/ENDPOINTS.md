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

