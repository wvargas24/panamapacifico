// Importar el CSS de Slick Slider
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

// Importar el JS de Slick Slider
import 'slick-carousel';

// Asegúrate de que jQuery también está cargado
import $ from 'jquery';

import sal from 'sal.js';
import 'sal.js/dist/sal.css';
import 'font-awesome/css/font-awesome.css';
import '@fortawesome/fontawesome-free/css/all.css';

import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

import Accordion from "accordion-js";
import "accordion-js/dist/accordion.min.css";

// Importar solo el JS de Bootstrap
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';


$(document).ready(function () {
    console.log("jQuery is ready!");
    sal();
    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    });

    // Verificar si existe el contenedor de tabs
    if ($('.custom-tabs').length > 0) {
        let currentIndex = 1; // El índice actual del panel
        const $tabsContainer = $('.custom-tabs .e-n-tabs-content');
        const $tabs = $tabsContainer.find('> div'); // Los paneles de contenido
        const $buttons = $('.custom-tabs .e-n-tab-title'); // Los botones de los tabs

        // Función para mover los paneles
        const moveTabs = (index) => {
            const totalTabs = $tabs.length;
            // console.log("Total tabs:", totalTabs);

            $tabs.each(function () {
                const $tab = $(this);
                const tabIndex = parseInt($tab.data('tab-index')); // Obtener el índice de cada panel
                let transformValue = '';
                let opacityValue = 0;
                let zIndexValue = 0;
                let width = 0;

                // Verificar si es el panel activo
                if (tabIndex === index) {
                    $tab.addClass('e-active');
                    transformValue = 'translate(-50%, 0)';
                    opacityValue = 1;
                    zIndexValue = 3;
                    width = 100;
                } else {
                    $tab.removeClass('e-active');

                    // Calcular los valores de opacidad, zIndex y transform según la distancia entre los índices
                    const diff = Math.abs(tabIndex - index);
                    // console.log("diff: ", diff);

                    // Desktop
                    if ($(window).width() >= 768) {
                        // Valores por defecto
                        transformValue = 'translate(-50%, -60px)';
                        opacityValue = 0;
                        zIndexValue = 0;
                        width = 100;

                        // Condiciones específicas para distancias
                        if (diff === 1) {
                            transformValue = 'translate(-50%, -20px)';
                            opacityValue = 0.75;
                            zIndexValue = 2;
                            width = 95;
                            if ((index === 2 && tabIndex === index - 1) || (index === 3 && tabIndex === index + 1)) {
                                transformValue = 'translate(-50%, -60px)';
                                opacityValue = 0.25;
                                zIndexValue = 0;
                                width = 85;
                            }
                        } else if (diff === 2) {
                            transformValue = 'translate(-50%, -40px)';
                            opacityValue = 0.5;
                            zIndexValue = 1;
                            width = 90;
                        } else if (diff === 3) {
                            transformValue = 'translate(-50%, -60px)';
                            opacityValue = 0.25;
                            zIndexValue = 0;
                            width = 85;
                        }
                    } else { // Mobile
                        transformValue = `translateY(${(tabIndex - index) * 100}px)`;
                        opacityValue = tabIndex === index ? 1 : 0.5;
                        zIndexValue = tabIndex === index ? 3 : 0;
                    }
                }

                // Aplicar estilos calculados
                $tab.css({
                    opacity: opacityValue,
                    zIndex: zIndexValue,
                    transform: transformValue,
                    width: `${width}%`
                });
            });
        };

        // Función para manejar el clic en los headings
        const handleTabClick = (event) => {
            const index = parseInt($(event.target).closest('button').data('tab-index')); // Obtener el índice desde el data-tab-index del parent
            currentIndex = index; // Establecer el índice actual
            // console.log("currentIndex:", currentIndex);
            moveTabs(currentIndex); // Mover los "tabs"
        };

        // Agregar evento de clic a cada heading
        $buttons.on('click', handleTabClick);

        // Inicializar con el primer panel activo
        moveTabs(currentIndex);

    }

    // Verificar si existe el botón de scroll-top
    if ($('#scroll-top').length > 0) {
        $('#scroll-top').on('click', function () {
            const $astScrollTop = $('#ast-scroll-top');
            if ($astScrollTop.length) {
                $astScrollTop.trigger('click'); // Dispara el evento de clic en #ast-scroll-top
            }
        });
    }

    // Verificar si existe el .custom-slider
    if ($('.custom-slider').length > 0) {
        $('.custom-slider').each(function () {
            const slider = $(this);
            const slidesPerView = slider.data('slides-per-view') || 5;
            const autoplay = slider.data('autoplay') === true;
            const autoplaySpeed = slider.data('autoplay-speed') || 3000;
            const spaceBetween = slider.data('space-between') || 10;
            const pauseOnHover = slider.data('pause-on-hover') === true;

            slider.slick({
                centerMode: true,
                centerPadding: '120px',
                slidesToShow: slidesPerView,
                slidesToScroll: 1,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                spaceBetween: spaceBetween,
                pauseOnHover: pauseOnHover,
                arrows: false,
                dots: false,
                infinite: true,
            });
        });
    }

    // Verificamos si existe el slider de amenities
    if ($('.amenities-slider').length > 0) {
        $('.amenities-slider').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true, // Habilitar flechas
            dots: false,
            centerMode: false,
            variableWidth: false,
            prevArrow: '<button type="button" class="slick-prev">Anterior</button>',
            nextArrow: '<button type="button" class="slick-next">Siguiente</button>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    }

    // Verificamos si existe el slider .project-gallery-slider
    if ($('.project-gallery-slider').length > 0) {
        $('.project-gallery-slider').each(function () {
            const slider = $(this);
            const slidesPerView = slider.data('slides-per-view') || 5;
            const autoplay = slider.data('autoplay') === true;
            const autoplaySpeed = slider.data('autoplay-speed') || 3000;
            const spaceBetween = slider.data('space-between') || 10;
            const pauseOnHover = slider.data('pause-on-hover') === true;

            slider.slick({
                centerMode: true,
                centerPadding: '10%',
                slidesToShow: slidesPerView,
                slidesToScroll: 1,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                spaceBetween: spaceBetween,
                pauseOnHover: pauseOnHover,
                arrows: true,
                dots: false,
                infinite: true,
                prevArrow: '<button type="button" class="slick-prev">Anterior</button>',
                nextArrow: '<button type="button" class="slick-next">Siguiente</button>',
            });
        });
    }

    // Verificamos si existe .accordion-container en la página
    if ($('.accordion-container').length > 0) {
        // Inicializamos el acordeón
        new Accordion(".accordion-container",
            {
                openOnInit: [0],
                //collapse: false
            }
        );
    }

    // Filtrar por búsqueda mientras se escribe
    let debounceTimer;

    // Filtrado de búsqueda cuando se escribe en el campo
    $('#search-filter').on('input', function () {
        var search = $(this).val(); // Captura el valor de búsqueda
        var category = $('.dropdown-item.active').data('category'); // Captura la categoría seleccionada desde el dropdown

        clearTimeout(debounceTimer);

        // Ejecuta el filtro después de 300ms de inactividad
        debounceTimer = setTimeout(function () {
            executeAjaxFilter(category, search);
        }, 300);
    });

    // Filtrar por categoría al seleccionar una opción
    $('.dropdown-item').on('click', function (e) {
        e.preventDefault();
        $('.dropdown-item').removeClass('active'); // Elimina la clase active de todos los elementos
        $(this).addClass('active');

        var category = $(this).data('category'); // Obtener la categoría seleccionada
        var search = $('#search-filter').val(); // Capturar el valor de búsqueda

        // Ejecutar AJAX para filtrar
        executeAjaxFilter(category, search);
    });

    // Función para ejecutar el AJAX con paginación y filtros
    function executeAjaxFilter(category = '', search = '', paged = 1, append = false) {
        var button = $('.load-more-btn');
        // Get the template id from #template-id
        var templateId = $('#template-id').val();

        $.ajax({
            url: contrastudioAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'filtered_posts',
                category: category,
                search: search,
                paged: paged,
                template_id: templateId,
            },
            beforeSend: function () {
                $('.content-loader').show();
            },
            success: function (response) {
                console.log(response);
                $('.content-loader').hide();

                // Verificar si la respuesta tiene más posts
                if (response.has_more) {
                    if (append) {
                        $('#posts-grid').append(response.posts_html);
                    } else {
                        $('#posts-grid').html(response.posts_html);
                        button.data('page', 2); // Reinicia el contador de páginas
                        button.text('Ver más').removeClass('disabled');
                    }
                } else {
                    // Si no hay más posts
                    button.text('No hay más posts').addClass('disabled');
                }
            },
            error: function () {
                $('.content-loader').hide();
                $('#posts-grid').html('<p>Hubo un error. Inténtalo de nuevo.</p>');
            },
        });
    }

    // Evento para el botón "Cargar más"
    $('.load-more-btn').on('click', function (e) {
        e.preventDefault();
        var paged = $(this).data('page');
        executeAjaxFilter('', '', paged, true); // Pasa los valores de paginación
        $(this).data('page', paged + 1); // Incrementa el número de página
    });



});


