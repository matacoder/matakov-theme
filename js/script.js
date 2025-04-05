/**
 * Основной JavaScript файл для темы
 */

document.addEventListener('DOMContentLoaded', function() {
    // Проверка загрузки Bootstrap
    console.log("Bootstrap доступен: ", typeof bootstrap !== 'undefined');
    if (typeof bootstrap === 'undefined') {
        console.error("Ошибка: Bootstrap не загружен! Меню может работать некорректно.");
    }
    
    // Добавляем Bootstrap классы к элементам WordPress
    
    // Таблицы
    const tables = document.querySelectorAll('.post-content table');
    tables.forEach(table => {
        table.classList.add('table', 'table-striped');
    });
    
    // Изображения
    const images = document.querySelectorAll('.post-content img');
    images.forEach(image => {
        image.classList.add('img-fluid');
        
        // Оборачиваем изображение в figure, если оно не обернуто
        const parent = image.parentNode;
        if (parent.nodeName !== 'FIGURE') {
            const figure = document.createElement('figure');
            figure.classList.add('figure');
            parent.insertBefore(figure, image);
            figure.appendChild(image);
            
            // Если у изображения есть подпись, добавляем ее
            if (image.hasAttribute('alt')) {
                const caption = document.createElement('figcaption');
                caption.classList.add('figure-caption', 'text-center', 'mt-2');
                caption.textContent = image.getAttribute('alt');
                figure.appendChild(caption);
            }
        }
    });

    // Обработка выпадающих меню
    const initDropdowns = function() {
        // Инициализируем все выпадающие меню для десктопов
        if (window.innerWidth >= 992 && typeof bootstrap !== 'undefined') {
            // Находим все элементы выпадающего меню
            const dropdownItems = document.querySelectorAll('.dropdown');
            
            dropdownItems.forEach(function(item) {
                // Найдем кнопку переключения и меню
                const toggleBtn = item.querySelector('.dropdown-toggle');
                const dropdownMenu = item.querySelector('.dropdown-menu');
                
                if (!toggleBtn || !dropdownMenu) return;
                
                // Отключим data атрибуты Bootstrap, чтобы управлять вручную
                toggleBtn.removeAttribute('data-bs-toggle');
                toggleBtn.removeAttribute('data-bs-auto-close');
                
                // Показываем меню при наведении
                item.addEventListener('mouseenter', function() {
                    dropdownMenu.classList.add('show');
                });
                
                // Скрываем меню при уходе курсора
                item.addEventListener('mouseleave', function() {
                    // Добавляем задержку, чтобы избежать мерцания
                    setTimeout(function() {
                        if (!item.matches(':hover')) {
                            dropdownMenu.classList.remove('show');
                        }
                    }, 200);
                });
                
                // Обработка клика для ссылок
                toggleBtn.addEventListener('click', function(e) {
                    // Если есть реальный URL и это не #, то позволяем навигацию
                    if (toggleBtn.getAttribute('href') && toggleBtn.getAttribute('href') !== '#') {
                        return true;
                    }
                    // Иначе предотвращаем действие по умолчанию
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Переключаем видимость меню при клике
                    dropdownMenu.classList.toggle('show');
                });
            });
        } 
        // Для мобильных устройств используем нативный Bootstrap dropdown
        else if (typeof bootstrap !== 'undefined') {
            // Убедимся, что все кнопки меню на мобильных имеют атрибут data-bs-toggle
            document.querySelectorAll('.dropdown-toggle').forEach(function(button) {
                button.setAttribute('data-bs-toggle', 'dropdown');
            });
            
            // Инициализируем все выпадающие меню через Bootstrap
            document.querySelectorAll('.dropdown-toggle').forEach(function(dropdownToggle) {
                new bootstrap.Dropdown(dropdownToggle);
            });
        }
    };
    
    // Инициализируем выпадающие меню при загрузке
    initDropdowns();
    
    // И при изменении размера окна
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            initDropdowns();
        }, 250);
    });
    
    // Закрытие всех меню при клике вне меню
    document.addEventListener('click', function(e) {
        if (window.innerWidth >= 992 && !e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
});
