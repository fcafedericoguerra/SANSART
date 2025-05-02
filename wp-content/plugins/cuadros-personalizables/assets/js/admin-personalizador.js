/**
 * Script mejorado para la gestión de mockups en el panel de administración
 * Soluciona problemas de carga de imagen, arrastre y puntos de control
 */
document.addEventListener("DOMContentLoaded", function () {
    const mockupUrlInput = document.getElementById("mockup-url");
    const productSelect = document.getElementById("producto-mockup");
    const saveMockupBtn = document.getElementById("guardar-mockup");
    const selectMockupBtn = document.getElementById("seleccionar-mockup");
    const resetAreaBtn = document.getElementById("reiniciar-area") || document.createElement('button'); // Fallback
    const canvasElement = document.getElementById("mockupCanvas");
    
    // Estados del editor
    let drawMode = true; // Modo dibujo (true) vs modo edición (false)
    let rectArea = null; // Referencia al rectángulo dibujado
    let isDrawing = false; // Flag para proceso de dibujo
    let startX = 0, startY = 0; // Punto inicial del mouse
    let originalImageSize = null; // Dimensiones originales de la imagen
    let mockupImage = null; // Referencia a la imagen del mockup
    
    // Crear canvas con dimensiones iniciales (se ajustará después)
    let canvas = new fabric.Canvas("mockupCanvas", {
        width: 800,
        height: 600,
        preserveObjectStacking: true,
        selection: false // Desactivar selección múltiple
    });
    
    // Inicializar estado de UI y botones
    function updateUIState() {
        // Actualizar estado de botones
        if (resetAreaBtn) {
            resetAreaBtn.disabled = !rectArea;
        }
        
        // Actualizar indicaciones visuales
        updateModeIndicator();
    }
    
    // Muestra un indicador del modo actual
    function updateModeIndicator() {
        // Eliminar indicador previo
        let indicator = document.querySelector('.mode-indicator');
        if (indicator) {
            indicator.remove();
        }
        
        // Crear nuevo indicador
        indicator = document.createElement('div');
        indicator.className = 'mode-indicator';
        
        const modeText = drawMode 
            ? 'MODO: DIBUJO - Haz clic y arrastra para definir el área editable'
            : 'MODO: EDICIÓN - Usa los puntos de control para ajustar el área';
            
        const modeColor = drawMode ? '#4e73df' : '#1cc88a';
        
        indicator.innerHTML = `<span style="background-color: ${modeColor}; color: white; padding: 5px 10px; border-radius: 3px;">${modeText}</span>`;
        indicator.style.position = 'absolute';
        indicator.style.top = '10px';
        indicator.style.left = '50%';
        indicator.style.transform = 'translateX(-50%)';
        indicator.style.zIndex = '1000';
        indicator.style.textAlign = 'center';
        
        const canvasContainer = canvasElement.parentElement;
        canvasContainer.appendChild(indicator);
    }

    // Crear botón para cambiar de modo si no existe
    function createModeToggle() {
        // Verificar si ya existe
        if (document.getElementById('toggle-draw-mode')) {
            return;
        }
        
        const modeToggle = document.createElement('button');
        modeToggle.id = 'toggle-draw-mode';
        modeToggle.className = 'button';
        modeToggle.textContent = 'Cambiar Modo';
        modeToggle.style.marginLeft = '10px';
        
        modeToggle.addEventListener('click', function() {
            // Cambiar entre modos dibujo y edición
            drawMode = !drawMode;
            
            // Si estamos en modo dibujo, desactivar selección para permitir dibujar nuevo
            if (drawMode) {
                canvas.discardActiveObject();
                canvas.requestRenderAll();
            } else if (rectArea) {
                // Si tenemos un área y pasamos a edición, seleccionarla
                canvas.setActiveObject(rectArea);
                canvas.requestRenderAll();
            }
            
            updateUIState();
        });
        
        // Insertar después del botón de reiniciar área
        resetAreaBtn.insertAdjacentElement('afterend', modeToggle);
    }

    // Seleccionar mockup desde biblioteca de medios
    selectMockupBtn.addEventListener("click", function(e) {
        e.preventDefault();
        
        if (typeof wp === "undefined" || typeof wp.media === "undefined") {
            console.error("wp.media no está disponible");
            return;
        }

        let mediaUploader = wp.media({
            title: "Seleccionar Mockup",
            button: { text: "Usar esta imagen" },
            multiple: false
        });

        mediaUploader.on("select", function() {
            let attachment = mediaUploader.state().get("selection").first().toJSON();
            mockupUrlInput.value = attachment.url;
            
            // Guardar dimensiones originales
            originalImageSize = {
                width: attachment.width,
                height: attachment.height
            };
            
            // Reiniciar estado
            drawMode = true;
            if (rectArea) {
                canvas.remove(rectArea);
                rectArea = null;
            }
            
            // Cargar imagen con método mejorado
            cargarMockupMejorado(attachment.url);
        });

        mediaUploader.open();
    });

    // Método mejorado para cargar mockup (soluciona problema 1)
    function cargarMockupMejorado(url) {
        console.log("Cargando mockup con método mejorado:", url);
        
        // Mostrar indicador de carga
        mostrarCargando();
        
        // Limpiar canvas
        canvas.clear();
        mockupImage = null;
        rectArea = null;
        
        // Cargar imagen con elemento nativo para obtener dimensiones correctas
        const imgElement = new Image();
        imgElement.crossOrigin = "anonymous";
        
        imgElement.onload = function() {
            // Una vez cargada, procesarla
            procesarImagenMockup(imgElement, url);
        };
        
        imgElement.onerror = function() {
            ocultarCargando();
            mostrarError("Error al cargar la imagen. Verifica la URL e intenta nuevamente.");
        };
        
        // Agregar parámetro para evitar caché
        imgElement.src = url + (url.includes('?') ? '&' : '?') + 'nocache=' + new Date().getTime();
    }
    
    // Procesar imagen mockup (mantiene resolución original)
    function procesarImagenMockup(imgElement, url) {
        ocultarCargando();
        
        const imgWidth = imgElement.width;
        const imgHeight = imgElement.height;
        
        console.log("Imagen cargada con dimensiones:", imgWidth, "x", imgHeight);
        
        // Calcular dimensiones para el canvas basadas en el contenedor
        const canvasContainer = canvasElement.parentElement;
        const containerWidth = canvasContainer.clientWidth - 40; // Margen para el contenedor
        
        // Mantener relación de aspecto
        let canvasWidth, canvasHeight, scale;
        
        // Escalar si es necesario para caber en pantalla pero preservar proporción
        if (imgWidth > containerWidth) {
            canvasWidth = containerWidth;
            canvasHeight = (imgHeight * containerWidth) / imgWidth;
            scale = containerWidth / imgWidth;
        } else {
            // Usar dimensiones originales si caben
            canvasWidth = imgWidth;
            canvasHeight = imgHeight;
            scale = 1;
        }
        
        // Ajustar canvas
        canvas.setWidth(canvasWidth);
        canvas.setHeight(canvasHeight);
        
        // Crear imagen de Fabric manteniendo la resolución original
        fabric.Image.fromURL(url, function(img) {
            if (!img) {
                mostrarError("Error al procesar la imagen.");
                return;
            }
            
            // Configurar imagen
            img.set({
                scaleX: scale,
                scaleY: scale,
                originX: 'left', 
                originY: 'top',
                left: 0,
                top: 0,
                selectable: false,
                evented: false
            });
            
            // Guardar referencia y actualizar canvas
            mockupImage = img;
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            
            // Crear instrucciones para el usuario
            mostrarInstrucciones();
            
            // Habilitar botón guardar
            saveMockupBtn.disabled = false;
            
            // Actualizar UI
            updateUIState();
        }, { crossOrigin: 'anonymous' });
    }
    
    // Mostrar indicador de carga
    function mostrarCargando() {
        // Eliminar si ya existe
        ocultarCargando();
        
        const loadingEl = document.createElement('div');
        loadingEl.className = 'admin-loading-indicator';
        loadingEl.innerHTML = '<div class="spinner is-active"></div><p>Cargando imagen...</p>';
        loadingEl.style.position = 'absolute';
        loadingEl.style.top = '0';
        loadingEl.style.left = '0';
        loadingEl.style.width = '100%';
        loadingEl.style.height = '100%';
        loadingEl.style.backgroundColor = 'rgba(255,255,255,0.8)';
        loadingEl.style.display = 'flex';
        loadingEl.style.flexDirection = 'column';
        loadingEl.style.alignItems = 'center';
        loadingEl.style.justifyContent = 'center';
        loadingEl.style.zIndex = '1000';
        
        const canvasContainer = canvasElement.parentElement;
        canvasContainer.appendChild(loadingEl);
    }
    
    // Ocultar indicador de carga
    function ocultarCargando() {
        const loadingEl = document.querySelector('.admin-loading-indicator');
        if (loadingEl) {
            loadingEl.remove();
        }
    }
    
    // Mostrar mensaje de error
    function mostrarError(mensaje) {
        const errorEl = document.createElement('div');
        errorEl.className = 'admin-error-message';
        errorEl.innerHTML = `<p>${mensaje}</p>`;
        errorEl.style.color = '#721c24';
        errorEl.style.backgroundColor = '#f8d7da';
        errorEl.style.padding = '10px';
        errorEl.style.marginTop = '10px';
        errorEl.style.borderRadius = '4px';
        errorEl.style.textAlign = 'center';
        
        // Insertar después del canvas
        canvasElement.insertAdjacentElement('afterend', errorEl);
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            errorEl.remove();
        }, 5000);
    }
    
    // Mostrar instrucciones para el usuario
    function mostrarInstrucciones() {
        let instructions = document.querySelector('.mockup-instructions');
        if (!instructions) {
            instructions = document.createElement('div');
            instructions.className = 'mockup-instructions';
            
            const canvasContainer = canvasElement.parentElement;
            canvasContainer.appendChild(instructions);
        }
        
        instructions.innerHTML = '<p>Haz clic y arrastra para definir el área editable donde los usuarios podrán colocar sus imágenes.</p>';
        instructions.style.marginTop = '10px';
        instructions.style.fontSize = '13px';
        instructions.style.color = '#666';
        instructions.style.fontStyle = 'italic';
    }

    // Función para restablecer el área editable
    function resetArea() {
        if (rectArea) {
            canvas.remove(rectArea);
            rectArea = null;
        }
        drawMode = true;
        updateUIState();
        canvas.renderAll();
    }

    // Asignar evento al botón de reinicio
    resetAreaBtn.addEventListener("click", function() {
        resetArea();
    });

    // Crear botón de reinicio si no existe
    if (!document.getElementById('reiniciar-area')) {
        const resetBtn = document.createElement('button');
        resetBtn.id = 'reiniciar-area';
        resetBtn.className = 'button button-secondary';
        resetBtn.textContent = 'Reiniciar área editable';
        resetBtn.style.marginLeft = '10px';
        resetBtn.disabled = true;
        
        // Insertar después del botón guardar
        saveMockupBtn.insertAdjacentElement('afterend', resetBtn);
        
        resetBtn.addEventListener("click", function() {
            resetArea();
        });
    }
    
    // Crear botón para cambiar modo
    createModeToggle();

    // MEJORADO: Evento mouse:down - Soluciona problema 2 y 3
    canvas.on("mouse:down", function(opt) {
        // Solo dibujar en modo dibujo
        if (!drawMode) return;
        
        const pointer = canvas.getPointer(opt.e);
        isDrawing = true;
        startX = pointer.x;
        startY = pointer.y;

        // Eliminar área anterior si existe
        if (rectArea) {
            canvas.remove(rectArea);
            rectArea = null;
        }

        // Crear rect con estilo mejorado
        rectArea = new fabric.Rect({
            left: startX,
            top: startY,
            width: 0,
            height: 0,
            fill: "rgba(73, 80, 246, 0.15)", // Azul más moderno y transparente
            stroke: "#4950f6",
            strokeWidth: 2,
            strokeDashArray: [5, 5],
            selectable: false
        });
        
        canvas.add(rectArea);
        canvas.renderAll();
    });

    // MEJORADO: Evento mouse:move - Mejora visibilidad al dibujar
    canvas.on("mouse:move", function(opt) {
        if (!isDrawing || !rectArea || !drawMode) return;
        
        const pointer = canvas.getPointer(opt.e);
        
        // Calcular dimensiones positivas independientemente de dirección
        const width = Math.abs(pointer.x - startX);
        const height = Math.abs(pointer.y - startY);
        const left = pointer.x < startX ? pointer.x : startX;
        const top = pointer.y < startY ? pointer.y : startY;
        
        // Actualizar rectángulo con nuevas dimensiones
        rectArea.set({
            left: left,
            top: top,
            width: width,
            height: height
        });
        
        // Actualizar solo lo necesario para mejor rendimiento
        canvas.renderAll();
    });

    // MEJORADO: Evento mouse:up - Soluciona problema con los puntos de control
    canvas.on("mouse:up", function() {
        if (!isDrawing || !rectArea || !drawMode) return;
        
        isDrawing = false;
        
        // Verificar tamaño mínimo
        if (rectArea.width < 20 || rectArea.height < 20) {
            canvas.remove(rectArea);
            rectArea = null;
            alert("El área es demasiado pequeña. Intenta dibujar un área más grande.");
            return;
        }
        
        // Cambiar a modo edición
        drawMode = false;
        
        // Guardar propiedades actuales
        const rectProps = {
            left: rectArea.left,
            top: rectArea.top,
            width: rectArea.width,
            height: rectArea.height
        };
        
        // Eliminar rectángulo actual
        canvas.remove(rectArea);
        
        // Crear nuevo rectángulo con controles mejorados para edición
        rectArea = new fabric.Rect({
            left: rectProps.left,
            top: rectProps.top,
            width: rectProps.width,
            height: rectProps.height,
            fill: "rgba(73, 80, 246, 0.15)",
            stroke: "#4950f6",
            strokeWidth: 2,
            strokeDashArray: [5, 5],
            selectable: true,
            hasControls: true,
            hasBorders: true,
            lockRotation: true,
            cornerColor: 'rgba(73, 80, 246, 0.8)',
            cornerStrokeColor: 'white',
            borderColor: '#4950f6',
            cornerSize: 20, // Control más grande para fácil selección
            cornerStyle: 'circle',
            transparentCorners: false,
            borderScaleFactor: 2
        });
        
        // Mejorar controles
        rectArea.setControlsVisibility({
            mt: true, mb: true, ml: true, mr: true,
            tl: true, tr: true, bl: true, br: true,
            mtr: false // No rotación
        });
        
        // Código especial para arreglar el comportamiento de los controles
        rectArea.controls = fabric.Object.prototype.controls;
        rectArea.cornerStyle = 'circle';
        rectArea.cornerSize = 20;
        
        // Añadir y seleccionar
        canvas.add(rectArea);
        canvas.setActiveObject(rectArea);
        
        // Traer al frente
        canvas.bringToFront(rectArea);
        canvas.renderAll();
        
        // Actualizar UI
        updateUIState();
    });

    // Manipuladores de eventos mejorados para selección y controles
    canvas.on("selection:created", function(opt) {
        if (!drawMode) {
            const activeObj = opt.selected[0];
            if (activeObj !== rectArea) {
                canvas.discardActiveObject();
                canvas.setActiveObject(rectArea);
                canvas.requestRenderAll();
            }
        }
    });
    
    // Asegurar que el rectángulo esté siempre por encima
    canvas.on("object:modified", function(opt) {
        if (opt.target === rectArea) {
            canvas.bringToFront(rectArea);
        }
    });

    // Guardar mockup y área - MEJORADO
    saveMockupBtn.addEventListener("click", function() {
        const productoID = productSelect.value;
        const mockupUrl = mockupUrlInput.value;

        if (!mockupUrl) {
            mostrarError("Primero selecciona un mockup desde la biblioteca de medios.");
            return;
        }
        
        if (!productoID) {
            mostrarError("Selecciona un producto para asociar el mockup.");
            return;
        }
        
        if (!rectArea) {
            mostrarError("Dibuja el área editable en el mockup antes de guardar.");
            return;
        }

        // Calcular valores relativos para mejor compatibilidad entre diferentes tamaños
        const areaData = calcularAreaRelativa();
        
        // Mostrar indicador de guardado
        const savingEl = document.createElement('div');
        savingEl.className = 'admin-saving-indicator';
        savingEl.innerHTML = '<div class="spinner is-active"></div><p>Guardando...</p>';
        savingEl.style.position = 'fixed';
        savingEl.style.top = '50%';
        savingEl.style.left = '50%';
        savingEl.style.transform = 'translate(-50%, -50%)';
        savingEl.style.backgroundColor = 'rgba(255,255,255,0.9)';
        savingEl.style.padding = '20px';
        savingEl.style.borderRadius = '5px';
        savingEl.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
        savingEl.style.zIndex = '10000';
        document.body.appendChild(savingEl);

        // Enviar datos al backend
        jQuery.post(ajaxurl, {
            action: "guardar_mockup",
            mockup_url: mockupUrl,
            producto_id: productoID,
            area_json: JSON.stringify(areaData)
        }, function(response) {
            // Eliminar indicador
            savingEl.remove();
            
            if (response.success) {
                mostrarMensajeExito("Mockup y área editable guardados correctamente.");
            } else {
                mostrarError("Error al guardar el mockup: " + response.data);
            }
        }).fail(function() {
            savingEl.remove();
            mostrarError("Error de conexión al guardar el mockup.");
        });
    });
    
    // Calcular valores relativos del área editable
    function calcularAreaRelativa() {
        // Calcular factores de escala según imagen original
        const canvasScale = mockupImage ? mockupImage.scaleX : 1;
        const offsetLeft = mockupImage ? mockupImage.left : 0;
        const offsetTop = mockupImage ? mockupImage.top : 0;
        
        // Calcular área en escala original
        const areaData = {
            x: (rectArea.left - offsetLeft) / canvasScale,
            y: (rectArea.top - offsetTop) / canvasScale,
            width: rectArea.width / canvasScale,
            height: rectArea.height / canvasScale,
            
            // Añadir datos normalizados (porcentajes) para mejor compatibilidad
            normalized: {
                x: rectArea.left / canvas.width,
                y: rectArea.top / canvas.height,
                width: rectArea.width / canvas.width,
                height: rectArea.height / canvas.height
            }
        };
        
        return areaData;
    }
    
    // Mostrar mensaje de éxito
    function mostrarMensajeExito(mensaje) {
        const msgEl = document.createElement('div');
        msgEl.className = 'admin-success-message';
        msgEl.innerHTML = `<p>✓ ${mensaje}</p>`;
        msgEl.style.color = '#155724';
        msgEl.style.backgroundColor = '#d4edda';
        msgEl.style.padding = '10px';
        msgEl.style.marginTop = '10px';
        msgEl.style.borderRadius = '4px';
        msgEl.style.textAlign = 'center';
        
        // Insertar después del canvas
        canvasElement.insertAdjacentElement('afterend', msgEl);
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            msgEl.remove();
        }, 5000);
    }
    
    // Inicializar estado de la UI
    updateUIState();
    
    // Deshabilitar botón guardar hasta seleccionar imagen
    saveMockupBtn.disabled = true;

    function mostrarErrorEnCanvas(msg){
        const colLeft = document.querySelector('.col-left');
        if(!colLeft) return;
        const err = document.createElement('div');
        err.className = 'error-message';
        err.textContent = msg;
        colLeft.appendChild(err);
      }
      
    
    // Ajustar canvas en redimensiones de ventana
    window.addEventListener('resize', function() {

        if (canvas && mockupUrlInput.value && mockupImage) {
            const newContainerWidth = canvasElement.parentElement.clientWidth;
            
            // Solo actualizar con cambios significativos
            if (Math.abs(newContainerWidth - canvas.width) > 50) {
                cargarMockupMejorado(mockupUrlInput.value);
            }
        }
    });
});