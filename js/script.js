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
        // Находим все элементы .dropdown-toggle
        const dropdownToggleList = document.querySelectorAll('.dropdown-toggle');
        
        // Создаем массив выпадающих меню
        const dropdownList = [];
        
        // Принудительно инициализируем все выпадающие меню через Bootstrap API
        if (typeof bootstrap !== 'undefined') {
            dropdownToggleList.forEach(function(dropdownToggle) {
                const dropdown = new bootstrap.Dropdown(dropdownToggle, {
                    autoClose: window.innerWidth < 992 ? 'true' : 'outside'
                });
                // Сохраняем экземпляр для дальнейшего использования
                dropdownList.push(dropdown);
            });
        }
        
        // Для десктопов (>= 992px) используем hover для открытия меню
        if (window.innerWidth >= 992) {
            const dropdownItems = document.querySelectorAll('.dropdown');
            dropdownItems.forEach(item => {
                const dropdownToggle = item.querySelector('.dropdown-toggle');
                const dropdownMenu = item.querySelector('.dropdown-menu');
                let dropdownInstance = null;
                
                // Найдем экземпляр Bootstrap Dropdown
                if (dropdownToggle && typeof bootstrap !== 'undefined') {
                    dropdownInstance = bootstrap.Dropdown.getInstance(dropdownToggle);
                }
                
                item.addEventListener('mouseenter', function() {
                    if (dropdownInstance) {
                        dropdownInstance.show();
                    } else if (dropdownMenu) {
                        dropdownMenu.classList.add('show');
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    // Не закрываем подменю сразу, чтобы пользователь успел перейти к нему
                    setTimeout(() => {
                        // Проверяем, не находится ли курсор над элементом
                        if (!item.matches(':hover')) {
                            if (dropdownInstance) {
                                dropdownInstance.hide();
                            } else if (dropdownMenu) {
                                dropdownMenu.classList.remove('show');
                            }
                        }
                    }, 200);
                });
                
                // Обработка клика по ссылкам с подменю
                if (dropdownToggle) {
                    dropdownToggle.addEventListener('click', function(e) {
                        if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                            // Если есть реальный URL, позволяем переход
                            return true;
                        }
                        // Иначе предотвращаем действие по умолчанию
                        e.preventDefault();
                    });
                }
            });
        }
        
        // Закрытие выпадающих меню при клике вне их
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown') && window.innerWidth >= 992) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    };
    
    // Инициализируем выпадающие меню при загрузке
    initDropdowns();
    
    // И при изменении размера окна
    window.addEventListener('resize', function() {
        initDropdowns();
    });
    
    // Исправление активности подменю на мобильных устройствах
    document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
        element.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                // На мобильных устройствах позволяем Bootstrap API управлять меню
                // Дополнительное действие не требуется
            }
        });
    });
});
