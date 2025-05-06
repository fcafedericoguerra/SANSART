/*  Personalizador de cuadros – versión parche 25-abr-2025
 *  Cambios implementados en esta edición:
 *  1. initCanvas() → fija también width/height vía CSS para que el lienzo no se encoja.
 *  2. cargarMockup() → reescala areaObj usando el mismo factor «scale» que la imagen.
 *  3. obtenerTamañoProducto() → busca cualquier select de variación cuyo nombre incluya «tamano/tamaño»
 *     y recalcula DPI al cambiar la variación.
 *  (Resto del código sin cambios para mantener compatibilidad.)
 */

document.addEventListener("DOMContentLoaded", function () {
  /* ------------------------------ Elementos DOM ------------------------------ */
  const openBtn  = document.getElementById("btn-personalizar");
  const popup    = openBtn ? document.getElementById("popup-personalizar") : null;
  const closeBtn = popup   ? document.getElementById("cerrar-modal")       : null;
  let   confirmBtn = null; // se obtiene cuando el popup ya es visible

  const inputFoto   = document.getElementById("foto_subida");
  const inputHidden = document.getElementById("img_personalizada");
  const mockupDataEl= document.getElementById("mockup-data");
  const fileLabel   = document.querySelector(".file-selected-name");
  const zoomSlider  = document.getElementById("zoomRange");
  const zoomIn  = document.querySelector(".zoom-in");
  const zoomOut = document.querySelector(".zoom-out");
  const rotateBtn = document.getElementById("rotate90Btn");
  const personalizacionIdField = document.getElementById("personalizacion_id");

  if (!openBtn || !popup || !mockupDataEl) {
    console.error("Elementos esenciales no encontrados");
    return;
  }

  /* --------------------------- Variables de entorno -------------------------- */
  let personalizacionId = personalizacionIdField ? personalizacionIdField.value : 0;
  const ajaxUrl  = typeof personalizadorVars !== "undefined" ? personalizadorVars.ajaxurl  : ajaxurl;
  const nonce    = typeof personalizadorVars !== "undefined" ? personalizadorVars.nonce    : "";
  const productId= typeof personalizadorVars !== "undefined" ? personalizadorVars.product_id: 0;

  /* ------------------------------ Estado global ------------------------------ */
  let fabricCanvas = null;
  window.fabricCanvas = null;

/* --------------------------- Datos del mock-up ----------------------------- */
const mockupUrl = mockupDataEl.getAttribute("data-mockup-url")  || "";
const areaJson   = mockupDataEl.getAttribute("data-mockup-area") || "";
let areaObj      = { x: 0, y: 0, width: 0, height: 0 };

try {
  if (areaJson) areaObj = JSON.parse(areaJson);
} catch (e) {
  console.error("Área JSON inválida", e);
}

// ← Aquí guardamos la versión “raw”, sin escalar
const rawArea = { ...areaObj };


  /* -------------------------------- Flags UI --------------------------------- */
  let userImg  = null;
  let editMode = false;
  let currentZoom = 1;

  /* =========================== Funciones principales ========================= */
  
  /* ============================= Funciones base ============================== */
  function mostrarCargando(){
    const existing=document.querySelector('.fullscreen-loading');
    if(existing) return;
    const d=document.createElement('div');
    d.className='fullscreen-loading loading-indicator';
    d.innerHTML='<div class="spinner"></div><p>Cargando…</p>';
    document.body.appendChild(d);
  }
  function ocultarCargando(){
    const e=document.querySelector('.fullscreen-loading');
    if(e) e.remove();
  }
  function mostrarNotificacion(msg){
    const n=document.createElement('div');
    n.className='personalizado-notificacion';
    n.textContent=msg;
    document.body.appendChild(n);
    setTimeout(()=>n.classList.add('show'),10);
    setTimeout(()=>n.remove(),4000);
  }

  /* ------------------------------- Canvas init ------------------------------- */
  /**
 * Inicializa el canvas con configuración óptima
 * @returns {Object} El canvas de Fabric.js inicializado
 */
function initCanvas() {
  // Limpieza si ya existe
  if (fabricCanvas) {
    fabricCanvas.clear();
    fabricCanvas.dispose();
  }
  
  // Elemento del canvas
  const canvasElement = document.getElementById("mockupCanvas");
  if (!canvasElement) {
    console.error("Error: Canvas no encontrado");
    return null;
  }
  
  // Dimensiones fijas para mejor rendimiento
  const fixedWidth = 800;
  const fixedHeight = 600;
  
  // Establecer dimensiones
  canvasElement.width = fixedWidth;
  canvasElement.height = fixedHeight;
  
  // Dimensiones CSS para evitar deformación
  canvasElement.style.width = fixedWidth + "px";
  canvasElement.style.height = fixedHeight + "px";
  
  // Crear canvas de Fabric
  fabricCanvas = new fabric.Canvas("mockupCanvas", {
    width: fixedWidth,
    height: fixedHeight,
    preserveObjectStacking: true,
    selection: false,
    centeredScaling: true,
    centeredRotation: true,
    uniformScaling: true
  });
  
  // Guardar referencia global
  window.fabricCanvas = fabricCanvas;
  
  // Configurar eventos del canvas
  fabricCanvas.on('mouse:down', function(opt) {
    const pointer = fabricCanvas.getPointer(opt.e);
    console.log('Click en canvas:', pointer);
  });
  
  // Mensaje de diagnóstico
  console.log("Canvas inicializado:", {
    width: fabricCanvas.width,
    height: fabricCanvas.height
  });
  
  return fabricCanvas;
}

  /* ----------------------------- Cargar mock‑up ----------------------------- */
  function cargarMockup(url) {
    return new Promise((resolve, reject) => {
      if (!url)          return reject("URL mockup vacía");
      if (!fabricCanvas) return reject("Canvas no disponible");
  
      // 1) Resetea el área al raw inicial
      areaObj = { ...rawArea };
  
      // 2) URL absoluta + cache-buster
      let abs = /^https?:/.test(url) ? url
              : window.location.origin + (url.startsWith("/") ? url : "/" + url);
      abs += (abs.includes("?") ? "&" : "?") + "nocache=" + Date.now();
  
      fabric.Image.fromURL(abs, img => {
        if (!img) return reject("No se cargó mockup");
  
        // 3) Escalar y centrar la imagen
        const cw = 800, ch = 600;
        fabricCanvas.setWidth(cw);
        fabricCanvas.setHeight(ch);
  
        const scale = Math.min(cw / img.width, ch / img.height) * 0.95;
        img.set({
          scaleX:  scale,
          scaleY:  scale,
          originX: "center",
          originY: "center",
          left:    cw / 2,
          top:     ch / 2
        });
        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));
  
        // 4) Obtener bounds reales del mock-up
        const b = img.getBoundingRect(true, true);
        const bx = b.left, by = b.top;
  
        // 5) Escalar el área (ahora sí, desde rawArea)
        areaObj = {
          x:      bx + areaObj.x * scale,
          y:      by + areaObj.y * scale,
          width:  areaObj.width  * scale,
          height: areaObj.height * scale
        };
  
        // 6) Elimina rect anteriores y dibuja el nuevo
        fabricCanvas.getObjects('rect')
          .filter(o => !o.selectable && o.stroke === 'red')
          .forEach(o => fabricCanvas.remove(o));
  
        const rect = new fabric.Rect({
          left:           areaObj.x,
          top:            areaObj.y,
          width:          areaObj.width,
          height:         areaObj.height,
          fill:           "rgba(0,0,0,0)",
          stroke:         "red",
          strokeWidth:    2,
          strokeDashArray:[5,5],
          selectable:     false,
          evented:        false
        });
        fabricCanvas.add(rect);
  
        resolve();
      }, { crossOrigin: "anonymous" });
    });
  }
  
  /* -------------------------- Zoom & movimiento cover ------------------------- */
  /**
 * Versión mejorada de limitarMovimientoCover que mantiene la imagen dentro del área
 * @param {Object} img - La imagen de Fabric.js
 * @param {Object} area - El área editable
 * @param {Object} canvas - El canvas de Fabric.js
 */
function limitarMovimientoCover(img, area, canvas) {
  if (!img || !area) return;
  
  // Obtener el rectángulo de límites considerando rotación
  const bound = img.getBoundingRect(true, true);
  
  // Verificar si la imagen es lo suficientemente grande
  let needsRescale = false;
  let scaleFactor = 1;
  
  // Si alguna dimensión es menor que el área, necesitamos escalar
  if (bound.width < area.width) {
    scaleFactor = Math.max(scaleFactor, area.width / bound.width * 1.05);
    needsRescale = true;
  }
  
  if (bound.height < area.height) {
    scaleFactor = Math.max(scaleFactor, area.height / bound.height * 1.05);
    needsRescale = true;
  }
  
  // Si necesitamos reescalar, hacerlo y centrar
  if (needsRescale) {
    img.scaleX *= scaleFactor;
    img.scaleY *= scaleFactor;
    
    // Centrar después de escalar
    img.left = area.x + area.width / 2;
    img.top = area.y + area.height / 2;
    
    // Actualizar coordenadas
    img.setCoords();
    return;
  }
  
  // Si la imagen es suficientemente grande, asegurar que cubra el área
  
  // Verificar si alguna parte del área está descubierta
  const areaRight = area.x + area.width;
  const areaBottom = area.y + area.height;
  const boundRight = bound.left + bound.width;
  const boundBottom = bound.top + bound.height;
  
  // Ajustar posición horizontal
  if (bound.left > area.x) {
    // La imagen está demasiado a la derecha
    img.left -= (bound.left - area.x);
  } else if (boundRight < areaRight) {
    // La imagen está demasiado a la izquierda
    img.left += (areaRight - boundRight);
  }
  
  // Ajustar posición vertical
  if (bound.top > area.y) {
    // La imagen está demasiado abajo
    img.top -= (bound.top - area.y);
  } else if (boundBottom < areaBottom) {
    // La imagen está demasiado arriba
    img.top += (areaBottom - boundBottom);
  }
  
  // Actualizar coordenadas
  img.setCoords();
}

  /* -------------------------- ZoomControls ------------------------- */
  function setupZoomControls() {
    if (!zoomSlider) return;
    
    // Si no hay imagen, desactivar control
    if (!userImg) {
      zoomSlider.disabled = true;
      return;
    }
    
    // Calcular escala base necesaria para cubrir completamente el área
    const imgWidth = userImg.width || 100;
    const imgHeight = userImg.height || 100;
    
    const scaleX = (areaObj.width / imgWidth) * 1.05;
    const scaleY = (areaObj.height / imgHeight) * 1.05;
    const baseScale = Math.max(scaleX, scaleY);
    
    // Configurar slider con incrementos definidos
    zoomSlider.min = baseScale;     // Mínimo: escala que cubre exactamente el área
    zoomSlider.max = baseScale * 3; // Máximo: 3 veces la escala base
    zoomSlider.step = 0.1;          // Incrementos de 0.1 para control preciso
    zoomSlider.value = userImg.scaleX || baseScale;
    zoomSlider.disabled = false;
    
    // Guardar escala base para referencia
    userImg.baseScale = baseScale;
    
    // Remover manejadores previos para evitar duplicados
    zoomSlider.removeEventListener('input', zoomSliderHandler);
    zoomSlider.removeEventListener('change', zoomSliderHandler);
    
    // Definir manejador como función nombrada para poder removerlo
    function zoomSliderHandler() {
      if (!userImg) return;
      
      // Obtener valor del slider, asegurando que sea al menos la escala base
      const newScale = Math.max(parseFloat(zoomSlider.value), baseScale);
      
      // Guardar posición central
      const centerX = areaObj.x + areaObj.width / 2;
      const centerY = areaObj.y + areaObj.height / 2;
      
      // Aplicar escala
      userImg.set({
        scaleX: newScale,
        scaleY: newScale,
        left: centerX,
        top: centerY
      });
      
      // Aplicar recorte
      aplicarRecorte(userImg);
      
      // Renderizar cambios
      fabricCanvas.renderAll();
    }
    
    // Agregar manejador de eventos
    zoomSlider.addEventListener('input', zoomSliderHandler);
    zoomSlider.addEventListener('change', zoomSliderHandler);
    
    // Configurar botones de zoom + y -
    if (zoomIn) {
      zoomIn.onclick = function() {
        if (!userImg) return;
        
        // Incremento de 0.1 (un punto de zoom)
        const newValue = Math.min(parseFloat(zoomSlider.value) + 0.1, zoomSlider.max);
        zoomSlider.value = newValue;
        
        // Disparar evento 'input' manualmente
        const event = new Event('input', { 'bubbles': true, 'cancelable': true });
        zoomSlider.dispatchEvent(event);
      };
    }
    
    if (zoomOut) {
      zoomOut.onclick = function() {
        if (!userImg) return;
        
        // Decremento de 0.1 (un punto de zoom), pero nunca menor que baseScale
        const newValue = Math.max(parseFloat(zoomSlider.value) - 0.1, baseScale);
        zoomSlider.value = newValue;
        
        // Disparar evento 'input' manualmente
        const event = new Event('input', { 'bubbles': true, 'cancelable': true });
        zoomSlider.dispatchEvent(event);
      };
    }
    
    // Agregar botón de reset
    agregarBotonResetZoom(baseScale);
  }

  /* ------------------------ Aplicar recorte y centrado ----------------------- */
  function aplicarRecorteYCentrado() {
  if (!userImg || !areaObj || !fabricCanvas) return;
  
  // Calcular centro del área editable
  const centerX = areaObj.x + areaObj.width / 2;
  const centerY = areaObj.y + areaObj.height / 2;
  
  // Forzar centrado
  userImg.set({
    left: centerX,
    top: centerY,
    originX: 'center',
    originY: 'center'
  });
  
  // Aplicar recorte al área exacta
  userImg.clipPath = new fabric.Rect({
    left: areaObj.x,
    top: areaObj.y,
    width: areaObj.width,
    height: areaObj.height,
    absolutePositioned: true
  });
  
  // Actualizar coordenadas internas
  userImg.setCoords();
  }

  /* ---------------------- DPI helpers / tamaño variación ---------------------- */
  function obtenerTamañoProducto(){
    const sel=document.querySelector('select[name^="attribute_"][name*="tamano" i]');
    if(sel&&sel.value){ const m=sel.value.match(/(\d+)\s*[x×]\s*(\d+)/i); if(m) return `${m[1]}x${m[2]}`; }
    return "30x30";
  }

  /* ----------------- Verificar resolución al cambiar variación ---------------- */
  document.addEventListener('change',e=>{
    if(e.target.matches('select[name^="attribute_"][name*="tamano" i]') && userImg){ verificarResolucion({ width:userImg.width, height:userImg.height }); }
  });

  /* ----------------- Verificar resolución versus medida cuadro ---------------- */
  function verificarResolucion(img) {
    // 1) Extraemos la medida «30x30» o similar
    const size = obtenerTamañoProducto()
      .split('x')
      .map(n => parseInt(n, 10));
    if (size.length !== 2) {
      console.warn('Medida cuadro inválida');
      return true;
    }
  
    // 2) Calculamos el mínimo en px (DPI reducido a 75)
    const [cmW, cmH] = size;
    const dpi = 75; 
    const pxW = Math.round((cmW / 2.54) * dpi);
    const pxH = Math.round((cmH / 2.54) * dpi);
  
    // 3) Comprobamos si la imagen cumple
    const ok = img.width >= pxW && img.height >= pxH;
  
    // 4) Preparamos el mensaje
    const msg = document.createElement('div');
    msg.className = 'resolucion-mensaje ' + (ok ? 'resolucion-ok' : 'resolucion-error');
    msg.innerHTML = ok
      ? `✓ Resolución adecuada: ${img.width}×${img.height}px para ${cmW}×${cmH}cm (mín. ${pxW}×${pxH}px)`
      : `⚠️ Resolución baja: ${img.width}×${img.height}px (se recomiendan ${pxW}×${pxH}px)`;
  
    // 5) Limpiamos antiguos mensajes y mostramos el nuevo
    document.querySelectorAll('.resolucion-mensaje').forEach(el => el.remove());
    const rightCol = document.querySelector('.col-right');
    if (rightCol) rightCol.prepend(msg);
  
    // 6) Deshabilitamos o no el botón Confirmar
    const confirmBtn = document.getElementById('confirmar-personalizacion');
    if (confirmBtn) {
      confirmBtn.disabled = !ok;
      confirmBtn.classList.toggle('disabled', !ok);
    }
  
    return ok;
  }

  /* ------------------------------ Guardar AJAX ------------------------------- */
  function guardarPersonalizacion() {
    if (!userImg) 
      return Promise.reject('No hay imagen');
    
    // Generar imagen final como base64 para enviar al servidor
    const finalImg = fabricCanvas.toDataURL({ format: 'png', quality: 1 });
    
    // Incluimos areaSimple y dimensiones de canvas para la recarga exacta
    const imageState = {
      left: userImg.left,
      top: userImg.top,
      scaleX: userImg.scaleX,
      scaleY: userImg.scaleY,
      angle: userImg.angle || 0,
      areaSimple: { 
        x: areaObj.x, 
        y: areaObj.y, 
        width: areaObj.width, 
        height: areaObj.height 
      },
      canvasDimensions: {
        width: fabricCanvas.width,
        height: fabricCanvas.height
      }
    };
    
    return new Promise((resolve, reject) => {
      jQuery.post(ajaxUrl, {
        action: 'save_personalization',
        security: nonce,
        product_id: productId,
        image_data: finalImg,
        image_state: JSON.stringify(imageState)
      }, res => {
        if (res.success) {
          console.log('Personalización guardada con éxito:', res.data);
          
          // Ahora el servidor devuelve image_url en lugar de image_data
          if (inputHidden) {
            // Actualizar el campo oculto con la URL en lugar del base64
            inputHidden.value = res.data.image_url;
          }
          
          // Actualizar también el campo de ID
          if (personalizacionIdField) {
            personalizacionIdField.value = res.data.id;
          }
          
          resolve(res.data);
        } else {
          console.error('Error al guardar la personalización:', res.data);
          reject(res.data);
        }
      })
      .fail(err => {
        console.error('Error AJAX:', err);
        reject(err);
      });
    });
  }
  
  /* --------------------------- Personalización previa ------------------------- */
  function cargarPersonalizacion(extra={}){
    return jQuery.post(ajaxUrl,{ action:'load_personalization', product_id:productId, security:nonce, ...extra });
  }

  /* -------------------------- CargarPerzonalización ------------------------- */
  function cargarPersonalizacionGuardada(){
    if (!personalizacionId) 
      return Promise.resolve();
  
    return cargarPersonalizacion({ id: personalizacionId })
      .then(res => {
        if (!res.success) 
          throw new Error('No hay personalización previa');
        
        // Parseamos el JSON que nos devolvió el servidor
        const state = typeof res.data.image_state === 'string'
          ? JSON.parse(res.data.image_state)
          : res.data.image_state;
  
        return mostrarImagenPrevisualizada(res.data.image_data, state);
      })
      .catch(err => {
        console.error('Error al cargar personalización previa:', err);
      });
  }
  

  /* ---------------------- Mostrar imagen guardada/base64 ---------------------- */
  /**
 * @param {string} imageData  Base64 de la imagen
 * @param {object} state      Objeto con left, top, scaleX, scaleY, angle, areaSimple
 */
/**
 * @param {string} imageData Base64 de la imagen
 * @param {object} state     { left, top, scaleX, scaleY, angle, areaSimple, canvasDimensions }
 */
function mostrarImagenPrevisualizada(imageData, state) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => {
      // 1) limpia la imagen anterior
      if (userImg) fabricCanvas.remove(userImg);

      // 2) crea nueva con estado exacto
      userImg = new fabric.Image(img, {
        left:    state.left,
        top:     state.top,
        scaleX:  state.scaleX,
        scaleY:  state.scaleY,
        angle:   state.angle || 0,
        originX: 'center',
        originY: 'center',
        selectable: true,
        evented:    true
      });
      fabricCanvas.add(userImg);

      // 3) clip-path con coords originales
      const clip = new fabric.Rect({
        left:               state.areaSimple.x,
        top:                state.areaSimple.y,
        width:              state.areaSimple.width,
        height:             state.areaSimple.height,
        absolutePositioned: true
      });
      userImg.clipPath = clip;

      // 4) reactiva zoom y render
      setupZoomControls();
      fabricCanvas.renderAll();
      resolve();
    };
    img.onerror = () => reject('No se pudo cargar la imagen');
    img.src = imageData;
  });
}

  /* -------------------------- Colocar imagen en área -------------------------- */
  /**
 * Coloca la imagen dentro del área editable y configura el recorte
 * @param {Object} img - La imagen de Fabric.js
 */
  function colocarImagenEnArea(img) {
    if (!img || !areaObj || !fabricCanvas) return;
    
    try {
      // Calcular centro del área editable
      const cx = areaObj.x + areaObj.width / 2;
      const cy = areaObj.y + areaObj.height / 2;
      
      // Dimensiones originales de la imagen
      const imgWidth = img.width || 100;
      const imgHeight = img.height || 100;
      
      // Calcular escala para cubrir el área con margen
      const scaleX = (areaObj.width / imgWidth) * 1.05;
      const scaleY = (areaObj.height / imgHeight) * 1.05;
      const scale = Math.max(scaleX, scaleY);
      
      // Guardar escala base como propiedad
      img.baseScale = scale;
      
      console.log("Colocando imagen:", {
        area: { x: areaObj.x, y: areaObj.y, width: areaObj.width, height: areaObj.height },
        imagen: { width: imgWidth, height: imgHeight },
        escala: scale
      });
      
      // Configurar imagen
      img.set({
        left: cx,
        top: cy,
        originX: 'center',
        originY: 'center',
        scaleX: scale,
        scaleY: scale
      });
      
      // Aplicar recorte
      img.clipPath = new fabric.Rect({
        left: areaObj.x,
        top: areaObj.y,
        width: areaObj.width,
        height: areaObj.height,
        absolutePositioned: true
      });
      
      // Actualizar coordenadas
      img.setCoords();
      
      // Configurar zoom
      setupZoomControls();
      
      // Renderizar canvas
      fabricCanvas.renderAll();
    } catch (error) {
      console.error("Error al colocar imagen en área:", error);
    }
  }

   /* -------------------------- Controles de zoom -------------------------- */
/**
 * Resetea el zoom al valor base que cubra el área
 * @param {Number} baseScale - Escala base, si se proporciona
 */
function resetearZoom(baseScale) {
  if (!userImg || !areaObj || !fabricCanvas) return;
  
  // Calcular escala base si no se proporciona
  if (!baseScale) {
    const imgWidth = userImg.width || 100;
    const imgHeight = userImg.height || 100;
    
    const scaleX = (areaObj.width / imgWidth) * 1.05;
    const scaleY = (areaObj.height / imgHeight) * 1.05;
    baseScale = Math.max(scaleX, scaleY);
  }
  
  // Calcular centro del área
  const centerX = areaObj.x + areaObj.width / 2;
  const centerY = areaObj.y + areaObj.height / 2;
  
  // Aplicar escala base y centrar
  userImg.set({
    scaleX: baseScale,
    scaleY: baseScale,
    left: centerX,
    top: centerY,
    originX: 'center',
    originY: 'center'
  });
  
  // Aplicar recorte
  aplicarRecorte(userImg);
  
  // Actualizar slider si existe
  if (zoomSlider) {
    zoomSlider.value = baseScale;
  }
  
  // Actualizar canvas
  fabricCanvas.renderAll();
  
  console.log("Zoom reseteado a escala base:", baseScale);
}
/**
 * Agrega un botón para resetear el zoom al valor base
 * @param {Number} baseScale - La escala base que cubre el área
 */
function agregarBotonResetZoom(baseScale) {
  // Verificar si ya existe
  if (document.getElementById('zoom-reset')) return;
  
  // Buscar el contenedor de zoom
  const zoomControl = document.querySelector('.zoom-control');
  if (!zoomControl) return;
  
  // Crear botón
  const resetBtn = document.createElement('button');
  resetBtn.id = 'zoom-reset';
  resetBtn.type = 'button';
  resetBtn.className = 'zoom-reset-btn';
  resetBtn.innerHTML = '↺';
  resetBtn.title = 'Restablecer zoom';
  resetBtn.style.cssText = `
    margin-left: 10px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: #f7f7f7;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
  `;
  
  // Añadir al DOM después del slider
  const sliderContainer = zoomControl.querySelector('.zoom-slider-container');
  if (sliderContainer) {
    sliderContainer.appendChild(resetBtn);
  } else {
    zoomControl.appendChild(resetBtn);
  }
  
  // Evento de clic - usar una función que conozca la escala base
  resetBtn.onclick = function() {
    resetearZoom(baseScale);
  };
  
  // Efectos visuales
  resetBtn.onmouseover = function() {
    this.style.transform = 'rotate(-45deg)';
  };
  
  resetBtn.onmouseout = function() {
    this.style.transform = 'rotate(0)';
  };
}

  function setupImageControls(img){
    img.setControlsVisibility({ mt:false,mb:false,ml:false,mr:false, bl:false,br:false,tl:false,tr:false, mtr:true });
    [ 'moving','rotating','scaling','modified' ].forEach(evt=> img.on(evt,()=>limitarMovimientoCover(img,areaObj,fabricCanvas)) );
    fabricCanvas.setActiveObject(img);
  }

  /**
 * Configura los controles y eventos de la imagen para mantenerla dentro del área
 * @param {Object} img - La imagen de Fabric.js
 */
  function setupImageControls(img) {
  if (!img || !areaObj || !fabricCanvas) return;
  
  // Mostrar solo los controles necesarios
  img.setControlsVisibility({ 
    mt: false, mb: false, ml: false, mr: false, 
    bl: false, br: false, tl: false, tr: false, 
    mtr: true // Solo mantener rotación
  });
  
  // Configuración de controles
  img.cornerColor = 'rgba(102,153,255,0.8)';
  img.cornerStyle = 'circle';
  img.cornerSize = 12;
  img.transparentCorners = false;
  
  // Eliminar manejadores previos para evitar duplicados
  img.off('moving');
  img.off('moved');
  img.off('rotating');
  img.off('scaled');
  img.off('modified');
  
  // Evento durante el movimiento - actualizar en tiempo real
  img.on('moving', function() {
    constrainMovement(this);
  });
  
  // Evento después del movimiento - ajuste final
  img.on('moved', function() {
    // Forzar que esté dentro de los límites
    constrainMovement(this, true);
    
    // Mantener recorte
    aplicarRecorte(this);
    
    // Actualizar canvas
    fabricCanvas.renderAll();
  });
  
  // Evento durante rotación
  img.on('rotating', function() {
    // Mantener centrado
    const centerX = areaObj.x + areaObj.width / 2;
    const centerY = areaObj.y + areaObj.height / 2;
    
    this.set({
      left: centerX,
      top: centerY
    });
    
    // Actualizar recorte
    aplicarRecorte(this);
  });
  
  // Evento durante escalado
  img.on('scaling', function() {
    constrainMovement(this);
    aplicarRecorte(this);
  });
  
  // Después de cualquier modificación
  img.on('modified', function() {
    constrainMovement(this, true);
    aplicarRecorte(this);
    fabricCanvas.renderAll();
  });
  
  // Hacer la imagen el objeto activo
  fabricCanvas.setActiveObject(img);
}

/**
 * Restringe el movimiento de la imagen para mantenerla dentro del área editable
 * @param {Object} img - La imagen de Fabric.js
 * @param {Boolean} force - Forzar ajuste incluso si está en los límites
 */
function constrainMovement(img, force = false) {
  if (!img || !areaObj) return;
  
  // Obtener rectángulo de límites considerando rotación
  const imgBounds = img.getBoundingRect(true, true);
  
  // Calcular centro del área editable
  const areaCenterX = areaObj.x + areaObj.width / 2;
  const areaCenterY = areaObj.y + areaObj.height / 2;
  
  // Si la imagen es demasiado pequeña, forzar escala base
  if (imgBounds.width < areaObj.width || imgBounds.height < areaObj.height) {
    // Calcular escala base
    const baseScale = img.baseScale || calculateBaseScale(img);
    
    // Centrar y aplicar escala base
    img.set({
      scaleX: baseScale,
      scaleY: baseScale,
      left: areaCenterX,
      top: areaCenterY
    });
    
    // Actualizar coordenadas
    img.setCoords();
    
    // Actualizar slider si existe
    if (zoomSlider) {
      zoomSlider.value = baseScale;
    }
    
    return;
  }
  
  // Para imágenes suficientemente grandes, verificar cobertura del área
  
  // Calcular bordes
  const areaLeft = areaObj.x;
  const areaTop = areaObj.y;
  const areaRight = areaObj.x + areaObj.width;
  const areaBottom = areaObj.y + areaObj.height;
  
  // Variables para ajustes
  let newLeft = img.left;
  let newTop = img.top;
  let needsAdjustment = false;
  
  // Verificar límites horizontales
  if (imgBounds.left > areaLeft) {
    // Imagen demasiado a la derecha
    newLeft = img.left - (imgBounds.left - areaLeft);
    needsAdjustment = true;
  } else if (imgBounds.left + imgBounds.width < areaRight) {
    // Imagen demasiado a la izquierda
    newLeft = img.left + (areaRight - (imgBounds.left + imgBounds.width));
    needsAdjustment = true;
  }
  
  // Verificar límites verticales
  if (imgBounds.top > areaTop) {
    // Imagen demasiado abajo
    newTop = img.top - (imgBounds.top - areaTop);
    needsAdjustment = true;
  } else if (imgBounds.top + imgBounds.height < areaBottom) {
    // Imagen demasiado arriba
    newTop = img.top + (areaBottom - (imgBounds.top + imgBounds.height));
    needsAdjustment = true;
  }
  
  // Aplicar ajustes si es necesario
  if (needsAdjustment || force) {
    img.set({
      left: newLeft,
      top: newTop
    });
    
    // Actualizar coordenadas
    img.setCoords();
  }
}

/**
 * Calcula la escala base necesaria para cubrir el área
 * @param {Object} img - La imagen
 * @returns {Number} La escala base
 */
function calculateBaseScale(img) {
  if (!img || !areaObj) return 1;
  
  const imgWidth = img.width || 100;
  const imgHeight = img.height || 100;
  
  const scaleX = (areaObj.width / imgWidth) * 1.05;
  const scaleY = (areaObj.height / imgHeight) * 1.05;
  
  return Math.max(scaleX, scaleY);
}

/**
 * Aplica el recorte (clipPath) al área editable
 * @param {Object} img - La imagen de Fabric.js
 */
function aplicarRecorte(img) {
  if (!img || !areaObj) return;
  
  // Crear rectángulo de recorte con las dimensiones exactas del área editable
  img.clipPath = new fabric.Rect({
    left: areaObj.x,
    top: areaObj.y,
    width: areaObj.width,
    height: areaObj.height,
    absolutePositioned: true
  });
}
  /* ------------------------ Abrir popup y flujo completo ---------------------- */
  openBtn.addEventListener('click',()=>{
    popup.style.display='block';
    setTimeout(()=>{
      if(typeof fabric==='undefined'){
        const s=document.createElement('script');
        s.src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js';
        s.onload=()=>initCanvasAndLoadAll();
        document.head.appendChild(s);
      }else initCanvasAndLoadAll();
    },10);
  });

  function initCanvasAndLoadAll(){
    fabricCanvas=initCanvas(); if(!fabricCanvas) return;
    editMode=Boolean(personalizacionId); mostrarCargando();
    cargarMockup(mockupUrl).then(()=> editMode?cargarPersonalizacionGuardada():null ).finally(ocultarCargando);
  }

  /* ------------------------------ Subir imagen ------------------------------- */
  if (inputFoto) {
    inputFoto.addEventListener('change', e => {
      const file = e.target.files[0];
      if (!file) return;
      
      // Actualizar nombre del archivo en la UI
      if (fileLabel) {
        fileLabel.textContent = file.name;
      }
      
      // Mostrar indicador de carga
      mostrarCargando();
      
      // Leer el archivo
      const reader = new FileReader();
      reader.onload = ev => {
        // Imagen temporal para verificar resolución
        const imgTemp = new Image();
        imgTemp.onload = () => {
          console.log("Imagen cargada con resolución:", imgTemp.width, "x", imgTemp.height);
          
          // Verificar resolución adecuada
          if (!verificarResolucion(imgTemp)) {
            ocultarCargando();
            return;
          }
          
          // Cargar la imagen en Fabric
          fabric.Image.fromURL(ev.target.result, fbImg => {
            // Remover imagen anterior si existe
            if (userImg) {
              fabricCanvas.remove(userImg);
            }
            
            // Guardar referencia
            userImg = fbImg;
            
            // Configurar controles
            setupImageControls(userImg);
            
            // Añadir al canvas
            fabricCanvas.add(userImg);
            
            // Colocar en área editable
            colocarImagenEnArea(userImg);
            
            // Configurar zoom
            setupZoomControls();
            
            // Asegurar que el canvas se actualice
            fabricCanvas.renderAll();
            
            // Ocultar cargando
            ocultarCargando();
          }, { crossOrigin: 'anonymous' });
        };
        
        // Manejar error de carga
        imgTemp.onerror = () => {
          console.error("Error al cargar imagen");
          ocultarCargando();
          mostrarErrorEnCanvas("No se pudo cargar la imagen. Verifica el formato.");
        };
        
        imgTemp.src = ev.target.result;
      };
      
      // Manejar error de lectura
      reader.onerror = () => {
        console.error("Error al leer archivo");
        ocultarCargando();
        mostrarErrorEnCanvas("Error al leer el archivo.");
      };
      
      reader.readAsDataURL(file);
    });
  }

  /* ---------------------------- Rotar 90° rápido ------------------------------ */
  if (rotateBtn) {
    rotateBtn.onclick = function() {
      if (userImg) {
        rotarImagenManteniendoCentro(userImg, 90);
      }
    };
  }

  /* ------------------------------- Confirmar ------------------------------- */
  // Manejar el botón Confirmar
document.addEventListener('click', e => {
  if (!e.target.matches('#confirmar-personalizacion')) return;
  
  // Verificar si hay imagen
  if (!userImg) {
    alert('Por favor, sube una imagen primero.');
    return;
  }
  
  // Si el botón está deshabilitado, mostrar mensaje
  if (e.target.disabled || e.target.classList.contains('disabled')) {
    alert('La imagen tiene baja resolución. Por favor, sube una imagen de mayor calidad.');
    return;
  }
  
  // Mostrar indicador de carga
  mostrarCargando();
  
  // Guardar personalización
  guardarPersonalizacion()
    .then(data => {
      console.log('Personalización guardada:', data);
      
      // Guardar en campos ocultos
      if (inputHidden) {
        inputHidden.value = data.image_data || '';
      }
      
      if (personalizacionIdField && data.id) {
        personalizacionIdField.value = data.id;
      }
      
      // Actualizar botón personalizar
      if (openBtn) {
        openBtn.classList.add('personalizado');
        openBtn.textContent = 'Editar personalización';
      }
      
      // Cerrar popup
      popup.style.display = 'none';
      
      // Ocultar cargando
      ocultarCargando();
      
      // Mostrar notificación
      mostrarNotificacion('¡Tu personalización está lista!');
      
      // Habilitar botón agregar al carrito
      const addToCartBtn = document.querySelector('.single_add_to_cart_button');
      if (addToCartBtn) {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('disabled');
      }
    })
    .catch(error => {
      console.error('Error al guardar:', error);
      ocultarCargando();
      
      // Crear mensaje de error
      const errorMsg = typeof error === 'string' ? error : 'Error al guardar. Por favor, intenta nuevamente.';
      
      // Mostrar error en la interfaz
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.style.color = '#e74c3c';
      errorDiv.style.backgroundColor = '#fdeaea';
      errorDiv.style.padding = '15px';
      errorDiv.style.borderRadius = '6px';
      errorDiv.style.marginBottom = '15px';
      errorDiv.style.textAlign = 'center';
      errorDiv.innerHTML = `<strong>Error:</strong> ${errorMsg}`;
      
      // Insertar en columna derecha
      const rightCol = document.querySelector('.col-right');
      if (rightCol) {
        // Eliminar errores previos
        const prevError = rightCol.querySelector('.error-message');
        if (prevError) prevError.remove();
        
        // Insertar al inicio
        rightCol.insertBefore(errorDiv, rightCol.firstChild);
        
        // Auto-eliminar después de 10 segundos
        setTimeout(() => {
          if (errorDiv.parentNode) {
            errorDiv.remove();
          }
        }, 10000);
      } else {
        alert(errorMsg);
      }
    });
});

  function mostrarErrorEnCanvas(msg){
    const colLeft = document.querySelector('.col-left');
    if(!colLeft) return;
    const err = document.createElement('div');
    err.className = 'error-message';
    err.textContent = msg;
    colLeft.appendChild(err);
  }
  
  /* ----------------------------- Cerrar modal ------------------------------ */
  if(closeBtn){ closeBtn.addEventListener('click',e=>{ e.preventDefault(); popup.style.display='none'; }); }

  /**
 * Rota la imagen manteniendo su posición centrada en el área editable
 * @param {Object} img - La imagen de Fabric.js a rotar
 * @param {Number} angle - Ángulo en grados (normalmente 90)
 */
function rotarImagenManteniendoCentro(img, angle) {
  if (!img || !areaObj || !fabricCanvas) return;
  
  // Calcular centro del área editable
  const areaCenterX = areaObj.x + areaObj.width / 2;
  const areaCenterY = areaObj.y + areaObj.height / 2;
  
  // Preservar escala actual
  const currentScaleX = img.scaleX;
  const currentScaleY = img.scaleY;
  
  // Aplicar rotación
  img.angle = (img.angle || 0) + angle;
  
  // Forzar centrado
  img.set({
    left: areaCenterX,
    top: areaCenterY,
    originX: 'center',
    originY: 'center',
    scaleX: currentScaleX,
    scaleY: currentScaleY
  });
  
  // Actualizar coordenadas y recorte
  img.setCoords();
  
  // Aplicar clipPath (recorte) al área editable
  img.clipPath = new fabric.Rect({
    left: areaObj.x,
    top: areaObj.y,
    width: areaObj.width,
    height: areaObj.height,
    absolutePositioned: true
  });
  
  // Verificar si después de rotar la imagen cubre completamente el área
  const bound = img.getBoundingRect(true, true);
  
  // Si no cubre completamente, ajustar escala
  if (bound.width < areaObj.width || bound.height < areaObj.height) {
    const scaleX = (areaObj.width / bound.width) * 1.05;
    const scaleY = (areaObj.height / bound.height) * 1.05;
    const newScale = Math.max(scaleX, scaleY);
    
    img.set({
      scaleX: newScale * currentScaleX,
      scaleY: newScale * currentScaleY
    });
    
    // Actualizar coordenadas
    img.setCoords();
  }
  
  // Actualizar canvas
  fabricCanvas.renderAll();
}
});