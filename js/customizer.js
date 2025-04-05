/**
 * JavaScript для предпросмотра настроек кастомайзера
 */
(function($) {
    // Обновление основного цвета в реальном времени
    wp.customize('matakov_primary_color', function(value) {
        value.bind(function(newval) {
            // Обновляем CSS переменную
            document.documentElement.style.setProperty('--primary-color', newval);
        });
    });

    // Обновление цвета при наведении в реальном времени
    wp.customize('matakov_primary_color_hover', function(value) {
        value.bind(function(newval) {
            // Обновляем CSS переменную
            document.documentElement.style.setProperty('--primary-color-hover', newval);
        });
    });
})(jQuery);
