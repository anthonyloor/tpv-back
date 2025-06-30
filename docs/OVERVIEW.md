# Visión general del proyecto

Este backend implementa un conjunto de servicios REST para gestionar un Terminal de Punto de Venta (TPV).
El proyecto está desarrollado con **Symfony 7** y utiliza una base de datos PostgreSQL.

Las rutas se encuentran en `src/Controller` y la lógica de negocio en `src/Logic`.
Las entidades del dominio se definen dentro de `src/Entity` y varias de ellas
representan tablas de una instalación de PrestaShop.

Entre las funcionalidades principales se incluyen:

- Gestión de productos y stock
- Creación y seguimiento de pedidos
- Control de sesiones de punto de venta
- Administración de clientes, tiendas y empleados
- Movimientos de almacén y generación de etiquetas

Para la autenticación se emplea JWT mediante el bundle de Lexik.

Consulta [ENDPOINTS.md](ENDPOINTS.md) para la descripción detallada de las rutas disponibles.
