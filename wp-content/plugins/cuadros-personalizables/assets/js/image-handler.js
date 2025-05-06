/**
 * Mejoras para el manejo de imágenes personalizadas
 * Este script soluciona problemas de visualización en carrito y guardado de imágenes
 */
jQuery(document).ready(function($) {
    console.log("Inicializando manejador de imágenes mejorado...");
    
    // Monitoreo global para detectar cuando se cargan las personalización
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                // Buscar imágenes personalizadas recién añadidas
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.classList && node.classList.contains('cart-personalizado-preview')) {
                        fixImage(node);
                    } else if (node.querySelectorAll) {
                        const images = node.querySelectorAll('.cart-personalizado-preview');
                        images.forEach(fixImage);
                    }
                }
            }
        });
    });
    
    // Iniciar observador
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Mejorar visualización de imágenes en carrito
    function fixImage(imgElement) {
        // Si la imagen es base64, probablemente se muestre bien
        const src = imgElement.getAttribute('src');
        if (src && src.indexOf('data:image') === 0) {
            console.log("Imagen base64 detectada, verificando visualización");
            // Asegurar que los estilos se apliquen correctamente
            imgElement.style.maxWidth = '100px';
            imgElement.style.maxHeight = '100px';
            imgElement.style.display = 'block';
            imgElement.style.margin = '5px auto';
            imgElement.style.border = '1px solid #ddd';
            imgElement.style.borderRadius = '4px';
            return;
        }
        
        // Si es una URL y no se carga correctamente, intentar cargarla de nuevo
        if (src && src.indexOf('http') === 0) {
            console.log("Imagen URL detectada:", src);
            // Verificar si la imagen es válida
            const img = new Image();
            img.onload = function() {
                console.log("Imagen cargada correctamente:", src);
            };
            img.onerror = function() {
                console.error("Error al cargar imagen URL:", src);
                // Intentar recargar con un parámetro timestamp para evitar caché
                imgElement.src = src + (src.includes('?') ? '&' : '?') + 'nocache=' + new Date().getTime();
            };
            img.src = src;
        }
    }
    
    // Mejorar el manejo de personalización guardada
    function setupPersonalizacionHandlers() {
        // Solo proceder si estamos en una página de producto
        if (!$('form.cart').length) return;
        
        // Variables importantes
        const btnPersonalizar = $('#btn-personalizar');
        const imgPersonalizadaInput = $('#img_personalizada');
        const personalizacionIdInput = $('#personalizacion_id');
        
        // Si el usuario tiene una personalización guardada, cambiar el texto del botón
        if ((imgPersonalizadaInput.length && imgPersonalizadaInput.val()) || 
            (personalizacionIdInput.length && personalizacionIdInput.val())) {
            btnPersonalizar.addClass('personalizado');
            btnPersonalizar.text('Editar personalización');
        }
    }
    
    // Agregar lightbox para ver imagen en carrito
    function setupLightbox() {
        // Ver si tenemos imágenes personalizadas en el carrito
        const personalizaciones = $('.cart-personalizado-preview');
        if (!personalizaciones.length) return;
        
        // Por cada contenedor de personalización, agregar botón "Ver imagen"
        $('.personalizacion-actions').each(function() {
            // Verificar si ya existe el botón
            if ($(this).find('.view-personalizacion').length > 0) return;
            
            // Encontrar la imagen en el contenedor padre
            const container = $(this).closest('.personalizacion-preview-container');
            const img = container.find('.cart-personalizado-preview');
            if (!img.length) return;
            
            // Crear botón "Ver imagen"
            const viewBtn = $('<a href="#" class="button view-personalizacion">Ver imagen</a>');
            
            // Insertar antes del botón editar
            $(this).prepend(viewBtn);
            
            // Configurar evento de clic
            viewBtn.on('click', function(e) {
                e.preventDefault();
                showImageLightbox(img.attr('src'));
            });
        });
    }
    
    // Mostrar lightbox con la imagen personalizada
    function showImageLightbox(imageUrl) {
        // Eliminar cualquier lightbox existente
        $('.personalizacion-lightbox').remove();
        
        // Verificar URL de la imagen
        if (!imageUrl) {
            console.error("URL de imagen inválida para lightbox");
            return;
        }
        
        // Crear lightbox con el DOM API para evitar problemas de jQuery
        const lightbox = document.createElement('div');
        lightbox.className = 'personalizacion-lightbox';
        
        const overlay = document.createElement('div');
        overlay.className = 'personalizacion-overlay';
        
        const content = document.createElement('div');
        content.className = 'personalizacion-lightbox-content';
        
        const closeBtn = document.createElement('a');
        closeBtn.href = '#';
        closeBtn.className = 'personalizacion-lightbox-close';
        closeBtn.innerHTML = '&times;';
        
        // Función para cerrar el lightbox
        function closeHandler(e) {
            e.preventDefault();
            lightbox.classList.remove('active');
            setTimeout(() => lightbox.remove(), 300);
        }
        
        closeBtn.addEventListener('click', closeHandler);
        overlay.addEventListener('click', closeHandler);
        
        // Crear imagen con manejo de errores
        const imgEl = new Image();
        imgEl.className = 'lightbox-image';
        
        // Indicador de carga
        const loadingEl = document.createElement('div');
        loadingEl.className = 'loading-spinner';
        loadingEl.innerHTML = '<div class="spinner"></div><p>Cargando imagen...</p>';
        content.appendChild(loadingEl);
        
        // Manejar carga exitosa
        imgEl.onload = function() {
            // Eliminar indicador de carga
            loadingEl.remove();
            
            // Añadir imagen al contenido
            content.appendChild(imgEl);
            
            // Mostrar lightbox con animación
            setTimeout(() => lightbox.classList.add('active'), 10);
        };
        
        // Manejar error de carga
        imgEl.onerror = function() {
            // Eliminar indicador de carga
            loadingEl.remove();
            
            // Mostrar mensaje de error
            const errorMsg = document.createElement('div');
            errorMsg.className = 'error-message';
            errorMsg.textContent = 'No se pudo cargar la imagen personalizada.';
            content.appendChild(errorMsg);
            
            // Mostrar lightbox con animación
            setTimeout(() => lightbox.classList.add('active'), 10);
        };
        
        // Configurar imagen
        imgEl.alt = 'Imagen personalizada';
        imgEl.src = imageUrl;
        
        // Armar estructura del lightbox
        content.appendChild(closeBtn);
        lightbox.appendChild(overlay);
        lightbox.appendChild(content);
        document.body.appendChild(lightbox);
        
        // Mostrar con animación
        setTimeout(() => lightbox.classList.add('active'), 10);
    }
    
    // Corregir problema del botón "editar" que reinicia la edición
    function fixEditButton() {
        $('.edit-personalizacion').each(function() {
            const $this = $(this);
            const href = $this.attr('href');
            
            // Solo modificar si no ha sido modificado ya
            if (href && !href.includes('keep_personalization=1')) {
                // Añadir parámetro para mantener la personalización
                const newHref = href + (href.includes('?') ? '&' : '?') + 'keep_personalization=1';
                $this.attr('href', newHref);
            }
        });
    }
    
    // Ejecutar funciones cuando la página se carga
    $(document).ready(function() {
        setupPersonalizacionHandlers();
        setupLightbox();
        fixEditButton();
    });
    
    // También cuando se actualiza el carrito
    $(document.body).on('updated_cart_totals', function() {
        console.log("Carrito actualizado, aplicando mejoras...");
        setTimeout(function() {
            setupLightbox();
            fixEditButton();
            
            // Buscar todas las imágenes en el carrito y mejorarlas
            $('.cart-personalizado-preview').each(function() {
                fixImage(this);
            });
        }, 500);
    });
    
    // Personalizador actualizado
    $(document).on('personalizacion_actualizada', function() {
        console.log("Personalización actualizada, aplicando mejoras...");
        setupPersonalizacionHandlers();
    });
});