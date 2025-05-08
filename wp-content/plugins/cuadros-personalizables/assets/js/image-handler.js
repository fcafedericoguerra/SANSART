/**
 * Mejoras para el manejo de imágenes personalizadas
 * Este script soluciona problemas de visualización en carrito y guardado de imágenes
 */
jQuery(document).ready(function($) {
    console.log("Inicializando manejador de imágenes mejorado v2...");
    
    // Función para verificar carpeta de personalizaciones
    function verificarCarpetaPersonalizados() {
        // Podemos usar AJAX para verificar la carpeta en el servidor
        $.post(ajaxurl, {
            action: 'verificar_carpeta_personalizados',
            security: typeof personalizadorVars !== 'undefined' ? personalizadorVars.nonce : ''
        }, function(response) {
            if (response.success) {
                console.log("Carpeta personalizados verificada:", response.data);
            } else {
                console.error("Error al verificar carpeta personalizados:", response.data);
            }
        });
    }
    
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
        if (!src) {
            console.log("Imagen sin atributo src detectada, buscando URLs alternativas...");
            // Intentar encontrar URL desde el botón Ver imagen cercano
            const container = imgElement.closest('.personalizacion-preview-container');
            if (container) {
                const viewBtn = container.querySelector('.view-personalizacion');
                if (viewBtn) {
                    const viewSrc = viewBtn.getAttribute('href');
                    if (viewSrc) {
                        console.log("Recuperando URL desde botón Ver:", viewSrc);
                        imgElement.setAttribute('src', viewSrc);
                    }
                }
            }
            return;
        }
        
        // Si es base64, aplicar estilos directamente
        if (src && src.indexOf('data:image') === 0) {
            console.log("Imagen base64 detectada, verificando visualización");
            // Asegurar que los estilos se apliquen correctamente
            applyStyles(imgElement);
            return;
        }
        
        // Si es una URL y no se carga correctamente, intentar cargarla de nuevo
        if (src && src.indexOf('http') === 0) {
            console.log("Imagen URL detectada:", src);
            
            // Intentar con Base64
            buscarBase64ParaImagen(imgElement, src);
        }
    }
    
    // Función para buscar una versión base64 de la imagen
function buscarBase64ParaImagen(imgElement, originalSrc) {
    // Intentar obtener ID desde la URL
    const matches = originalSrc.match(/personalizado_(\d+)/);
    if (!matches || !matches[1]) {
        fallbackToErrorImage(imgElement);
        return;
    }
    
    const id = matches[1];
    console.log("Intentando recuperar base64 para personalización ID:", id);
    
    // Hacer solicitud AJAX para obtener datos base64 directamente
    $.post(ajaxurl || '/wp-admin/admin-ajax.php', {
        action: 'get_personalizacion_base64',
        id: id,
        security: typeof personalizadorVars !== 'undefined' ? personalizadorVars.nonce : ''
    }, function(response) {
        if (response.success && response.data) {
            console.log("Base64 recuperado correctamente");
            imgElement.src = response.data;
            applyStyles(imgElement);
        } else {
            console.error("No se pudo recuperar base64:", response);
            fallbackToErrorImage(imgElement);
        }
    }).fail(function() {
        console.error("Error en la solicitud AJAX");
        fallbackToErrorImage(imgElement);
    });
}
    // Manejar error de carga de imagen
    function handleImageError(imgElement, src) {
        // 1. Intentar añadir parámetro anti-caché
        const cacheBusterSrc = src + (src.includes('?') ? '&' : '?') + 'nocache=' + new Date().getTime();
        console.log("Intentando con anti-caché:", cacheBusterSrc);
        
        const img = new Image();
        img.onload = function() {
            console.log("Imagen cargada con anti-caché:", cacheBusterSrc);
            imgElement.src = cacheBusterSrc;
            applyStyles(imgElement);
        };
        img.onerror = function() {
            console.error("Sigue fallando, intentando convertir a ruta absoluta");
            
            // 2. Verificar si es una ruta relativa e intentar hacerla absoluta
            if (src.indexOf('/') === 0 && src.indexOf('//') !== 0) {
                const absPath = window.location.origin + src;
                tryWithAbsolutePath(imgElement, absPath);
            } else if (src.indexOf('http') !== 0) {
                const absPath = window.location.origin + '/' + src;
                tryWithAbsolutePath(imgElement, absPath);
            } else {
                // 3. Si todo falla, mostrar imagen de error
                fallbackToErrorImage(imgElement);
            }
        };
        img.src = cacheBusterSrc;
    }
    
    // Intentar con ruta absoluta
    function tryWithAbsolutePath(imgElement, absPath) {
        const img = new Image();
        img.onload = function() {
            console.log("Imagen cargada con ruta absoluta:", absPath);
            imgElement.src = absPath;
            applyStyles(imgElement);
        };
        img.onerror = function() {
            console.error("Ruta absoluta también falló:", absPath);
            fallbackToErrorImage(imgElement);
        };
        img.src = absPath;
    }
    
    // Última opción: imagen de error
    function fallbackToErrorImage(imgElement) {
        // Crear una imagen SVG básica como fallback
        const svgFallback = `data:image/svg+xml,${encodeURIComponent(`
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100%" height="100%" fill="#f8f8f8"/>
                <text x="50%" y="50%" font-family="Arial" font-size="12" fill="#999" text-anchor="middle">Imagen no disponible</text>
            </svg>
        `)}`;
        
        imgElement.src = svgFallback;
        imgElement.setAttribute('alt', 'Imagen no disponible');
        
        // Marcar para edición
        const container = imgElement.closest('.personalizacion-preview-container');
        if (container) {
            const viewBtn = container.querySelector('.view-personalizacion');
            if (viewBtn) {
                viewBtn.style.display = 'none'; // Ocultar botón Ver
            }
            
            // Agregar mensaje de error
            const errorMsg = document.createElement('p');
            errorMsg.style.color = '#e74c3c';
            errorMsg.style.fontSize = '12px';
            errorMsg.textContent = 'Error al cargar la imagen. Por favor, edita la personalización.';
            
            // Insertar antes de los botones
            const actionsDiv = container.querySelector('.personalizacion-actions');
            if (actionsDiv) {
                container.insertBefore(errorMsg, actionsDiv);
            } else {
                container.appendChild(errorMsg);
            }
        }
    }
    
    // Aplicar estilos a la imagen
    function applyStyles(imgElement) {
        imgElement.style.maxWidth = '100px';
        imgElement.style.maxHeight = '100px';
        imgElement.style.display = 'block';
        imgElement.style.margin = '5px auto';
        imgElement.style.border = '1px solid #ddd';
        imgElement.style.borderRadius = '4px';
        imgElement.style.objectFit = 'contain';
        imgElement.style.backgroundColor = '#f9f9f9';
    }
    
    // Corregir problema de los botones "Ver imagen"
    function fixViewButtons() {
        $('.view-personalizacion').each(function() {
            const $this = $(this);
            const href = $this.attr('href');
            
            if (!href || href === '#') {
                // Buscar imagen cercana para obtener src
                const container = $this.closest('.personalizacion-preview-container');
                if (container) {
                    const img = container.querySelector('.cart-personalizado-preview');
                    if (img && img.src) {
                        $this.attr('href', img.src);
                    } else {
                        // Si no hay imagen, ocultar el botón
                        $this.hide();
                    }
                }
            }
            
            // Asegurar que tenga evento click correcto
            $this.off('click').on('click', function(e) {
                e.preventDefault();
                const imgSrc = $(this).attr('href');
                if (imgSrc && imgSrc !== '#') {
                    showImageLightbox(imgSrc);
                } else {
                    alert('No se puede mostrar la imagen. Por favor, edita la personalización.');
                }
            });
        });
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
    
    // Mostrar lightbox con la imagen personalizada
    function showImageLightbox(imageUrl) {
        // Eliminar cualquier lightbox existente
        $('.personalizacion-lightbox').remove();
        
        // Verificar URL de la imagen
        if (!imageUrl) {
            console.error("URL de imagen inválida para lightbox");
            alert('No se puede mostrar la imagen. Por favor, edita la personalización.');
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
    
    // Ejecutar funciones de inicialización
    verificarCarpetaPersonalizados();
    fixViewButtons();
    fixEditButton();
    
    // Buscar todas las imágenes en el carrito y optimizarlas
    $('.cart-personalizado-preview').each(function() {
        fixImage(this);
    });
    
    // También cuando se actualiza el carrito
    $(document.body).on('updated_cart_totals', function() {
        console.log("Carrito actualizado, aplicando mejoras...");
        setTimeout(function() {
            fixViewButtons();
            fixEditButton();
            
            // Buscar todas las imágenes en el carrito y mejorarlas
            $('.cart-personalizado-preview').each(function() {
                fixImage(this);
            });
        }, 500);
    });
});