# Panama Pacifico

Este es un theme personalizado para WordPress que está diseñado para trabajar con **Elementor**, **Elementor Pro** y **ACF Pro**. Utiliza dependencias de Node.js para facilitar el desarrollo y la compilación del theme.

## Descripción

Este theme de WordPress es altamente personalizable y optimizado para usar con los plugins Elementor, Elementor Pro y ACF Pro. Está diseñado para facilitar el desarrollo y la personalización del diseño de páginas, asegurando una integración fluida con estas herramientas.

## Requisitos

Antes de comenzar, asegúrate de tener los siguientes requisitos instalados:

- **WordPress** instalado y configurado.
- **Elementor** (versión gratuita) y **Elementor Pro** instalados y activados.
- **ACF Pro** instalado y activado.
- **Node.js** y **npm** (Node Package Manager) instalados.

## Instalación

Sigue estos pasos para instalar y configurar el proyecto:

1. Clona el repositorio en tu máquina local:
    ```bash
    git clone https://github.com/wvargas24/panamapacifico.git
    ```

2. Navega al directorio del theme:
    ```bash
    cd astra-theme
    ```

3. Instala las dependencias necesarias con npm:
    ```bash
    npm install
    ```

## Desarrollo

Para comenzar a trabajar en el desarrollo del theme, ejecuta el siguiente comando que compilará los archivos y los mantendrá actualizados mientras trabajas:

```bash
npm run start
```
Este comando ejecuta el modo escucha (watch mode), que compila automáticamente los archivos cada vez que realices un cambio.

## Generación del Theme

Cuando termines de trabajar en el theme y necesites empaquetarlo para cargarlo en tu sitio de WordPress, utiliza el siguiente comando:

```bash
npm run compile
```
Este comando genera un archivo *ZIP* del theme, listo para ser cargado en tu sitio web de WordPress a través del panel de administración.

## Uso con Elementor y ACF Pro
Este theme está optimizado para trabajar con Elementor y ACF Pro. Asegúrate de que estos plugins estén instalados y activados en tu instalación de WordPress para aprovechar todas las funcionalidades personalizadas que se han implementado.

## Contribución
Si deseas contribuir al proyecto, sigue estos pasos:

1.  Haz un fork del repositorio.
2.  Crea una nueva rama para tus cambios:
   ```bash
    git checkout -b nombre-de-tu-rama
    ```
3.  Realiza tus modificaciones.
4.  Haz un commit de tus cambios y súbelos a tu fork.
5.  Envía un pull request con una descripción clara de los cambios realizados.

¡Gracias por usar este theme de WordPress!
