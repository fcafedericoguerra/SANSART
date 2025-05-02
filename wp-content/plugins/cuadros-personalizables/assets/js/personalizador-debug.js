/**
 * Script de diagnóstico y depuración para el frontend
 * 
 * Este script ayuda a diagnosticar problemas con el personalizador en el frontend.
 */

(function() {
    console.log("%c=== DIAGNÓSTICO DE CUADROS PERSONALIZABLES ===", "background: #3498db; color: white; padding: 5px; border-radius: 3px;");
    
    // Verificar elementos básicos
    const diagnosticoElementos = {
        'btn-personalizar': document.getElementById('btn-personalizar'),
        'popup-personalizar': document.getElementById('popup-personalizar'),
        'mockupCanvas': document.getElementById('mockupCanvas'),
        'mockup-data': document.getElementById('mockup-data'),
        'img_personalizada': document.getElementById('img_personalizada'),
        'form.cart': document.querySelector('form.cart')
    };
    
    console.log("Elementos DOM:", diagnosticoElementos);
    
    // Verificar variables de personalizador
    console.log("Variables del personalizador:", typeof personalizadorVars !== 'undefined' ? personalizadorVars : 'No definidas');
    
    // Verificar fabric.js
    console.log("Fabric.js disponible:", typeof fabric !== 'undefined' ? "✓" : "✗");
    
    // Verificar jQuery
    console.log("jQuery disponible:", typeof jQuery !== 'undefined' ? "✓" : "✗");
    
    // Verificar carga de archivos CSS
    const cssLoaded = Array.from(document.styleSheets).some(sheet => {
        try {
            if (sheet.href && sheet.href.includes('personalizador.css')) {
                return true;
            }
        } catch(e) {
            // Algunas hojas de estilo pueden dar error CORS al acceder
            return false;
        }
        return false;
    });
    
    console.log("CSS del personalizador cargado:", cssLoaded ? "✓" : "✗");
    
    // Verificar si estamos en modo edición
    const isEditMode = new URLSearchParams(window.location.search).get('edit_personalizacion') === '1';
    console.log("Modo edición:", isEditMode ? "✓ Activo" : "✗ Inactivo");
    
    // Probar carga de imagen
    if (diagnosticoElementos['mockup-data']) {
        const mockupUrl = diagnosticoElementos['mockup-data'].getAttribute('data-mockup-url');
        const mockupArea = diagnosticoElementos['mockup-data'].getAttribute('data-mockup-area');
        
        console.log("Mockup URL:", mockupUrl);
        console.log("Mockup Area:", mockupArea);
        
        if (mockupUrl) {
            const img = new Image();
            img.onload = function() {
                console.log("✅ Imagen de mockup carga correctamente:", this.width + "x" + this.height);
            };
            img.onerror = function() {
                console.error("❌ Error al cargar la imagen del mockup");
                
                // Intentar con URL absoluta
                if (mockupUrl.indexOf('http') !== 0) {
                    const absoluteUrl = window.location.origin + (mockupUrl.startsWith('/') ? '' : '/') + mockupUrl;
                    console.log("Intentando con URL absoluta:", absoluteUrl);
                    
                    const img2 = new Image();
                    img2.onload = function() {
                        console.log("✅ Imagen carga correctamente con URL absoluta:", this.width + "x" + this.height);
                        console.log("RECOMENDACIÓN: Usar esta URL absoluta en lugar de la relativa");
                    };
                    img2.onerror = function() {
                        console.error("❌ Error al cargar incluso con URL absoluta");
                        console.log("RECOMENDACIÓN: Verificar que el archivo existe y es accesible");
                    };
                    img2.src = absoluteUrl;
                }
            };
            img.src = mockupUrl;
        }
        
        // Verificar JSON del área
        if (mockupArea) {
            try {
                const areaObj = JSON.parse(mockupArea);
                console.log("✅ JSON del área válido:", areaObj);
                
                // Verificar que el área tenga las propiedades correctas
                const propiedadesRequeridas = ['x', 'y', 'width', 'height'];
                const tieneTodasPropiedades = propiedadesRequeridas.every(prop => areaObj.hasOwnProperty(prop));
                
                if (!tieneTodasPropiedades) {
                    console.warn("⚠️ El objeto del área no tiene todas las propiedades requeridas (x, y, width, height)");
                }
                
                // Verificar valores numéricos
                const valoresInvalidos = propiedadesRequeridas.filter(prop => 
                    areaObj.hasOwnProperty(prop) && (typeof areaObj[prop] !== 'number' || isNaN(areaObj[prop]))
                );
                
                if (valoresInvalidos.length > 0) {
                    console.warn(`⚠️ Propiedades con valores no numéricos: ${valoresInvalidos.join(', ')}`);
                }
            } catch (e) {
                console.error("❌ Error en JSON del área:", e.message);
                console.log("RECOMENDACIÓN: Verificar el formato del JSON del área");
            }
        } else {
            console.warn("⚠️ No se encontró información del área editable");
        }
    }
    
    // Monitorear clicks en botón personalizar
    if (diagnosticoElementos['btn-personalizar']) {
        const originalClick = diagnosticoElementos['btn-personalizar'].onclick;
        
        diagnosticoElementos['btn-personalizar'].addEventListener('click', function(e) {
            console.log("🔍 Botón personalizar clickeado");
            
            // Verificar popup
            setTimeout(function() {
                const popupVisible = diagnosticoElementos['popup-personalizar'] && 
                                    (diagnosticoElementos['popup-personalizar'].style.display === 'block' || 
                                     getComputedStyle(diagnosticoElementos['popup-personalizar']).display === 'block');
                
                console.log("Popup visible:", popupVisible ? "✓" : "✗");
                
                // Monitorear canvas
                setTimeout(function() {
                    if (window.fabricCanvas) {
                        console.log("✅ Canvas de Fabric.js inicializado correctamente");
                        
                        // Verificar dimensiones
                        console.log("Dimensiones del canvas:", {
                            width: window.fabricCanvas.width,
                            height: window.fabricCanvas.height
                        });
                        
                        // Verificar imagen de fondo
                        const tieneFondo = window.fabricCanvas.backgroundImage !== null;
                        console.log("Imagen de fondo:", tieneFondo ? "✓ Presente" : "✗ No presente");
                        
                        if (tieneFondo) {
                            console.log("Dimensiones de la imagen de fondo:", {
                                width: window.fabricCanvas.backgroundImage.width,
                                height: window.fabricCanvas.backgroundImage.height,
                                scaleX: window.fabricCanvas.backgroundImage.scaleX,
                                scaleY: window.fabricCanvas.backgroundImage.scaleY
                            });
                        }
                        
                        // Verificar objetos en el canvas
                        console.log("Objetos en el canvas:", window.fabricCanvas.getObjects().length);
                        
                        // Encontrar el rectángulo guía
                        const rectGuia = window.fabricCanvas.getObjects().find(obj => 
                            obj.type === 'rect' && obj.stroke === 'red' && !obj.selectable
                        );
                        
                        console.log("Rectángulo guía:", rectGuia ? "✓ Encontrado" : "✗ No encontrado");
                        
                        if (rectGuia) {
                            console.log("Propiedades del rectángulo guía:", {
                                left: rectGuia.left,
                                top: rectGuia.top,
                                width: rectGuia.width,
                                height: rectGuia.height
                            });
                        }
                    } else {
                        console.error("❌ Canvas de Fabric.js no inicializado");
                        
                        // Verificar si fabric está disponible pero el canvas no se inicializó
                        if (typeof fabric !== 'undefined') {
                            console.log("Fabric.js está disponible pero el canvas no se inicializó correctamente");
                            console.log("RECOMENDACIÓN: Verificar la función initCanvas en el código JS");
                        }
                    }
                }, 1000); // Esperar un segundo para que el canvas se inicialice
            }, 500); // Esperar medio segundo para que el popup aparezca
        }, { once: false });
    }
    
    // Verificar si hay errores en la consola
    const consoleErrors = [];
    const originalError = console.error;
    
    console.error = function() {
        consoleErrors.push(Array.from(arguments).join(' '));
        originalError.apply(console, arguments);
    };
    
    // Verificar funcionamiento de AJAX
    function probarAjax() {
        if (typeof jQuery !== 'undefined' && typeof personalizadorVars !== 'undefined' && personalizadorVars.ajaxurl) {
            jQuery.ajax({
                url: personalizadorVars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'heartbeat'  // Usar una acción que siempre existe
                },
                success: function() {
                    console.log("✅ AJAX funciona correctamente");
                },
                error: function(xhr, status, error) {
                    console.error("❌ Error en AJAX:", status, error);
                }
            });
        }
    }
    
    // Ejecutar prueba AJAX después de un tiempo
    setTimeout(probarAjax, 2000);
    
    // Mostrar resumen al final de la carga
    window.addEventListener('load', function() {
        setTimeout(function() {
            console.log("%c=== RESUMEN DEL DIAGNÓSTICO ===", "background: #3498db; color: white; padding: 5px; border-radius: 3px;");
            
            if (consoleErrors.length > 0) {
                console.log("❌ Se detectaron", consoleErrors.length, "errores en la consola");
            } else {
                console.log("✅ No se detectaron errores en la consola");
            }
            
            console.log("%c=== FIN DEL DIAGNÓSTICO ===", "background: #3498db; color: white; padding: 5px; border-radius: 3px;");
        }, 3000);
    });
})();