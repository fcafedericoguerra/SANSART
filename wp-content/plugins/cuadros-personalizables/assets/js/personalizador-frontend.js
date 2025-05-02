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
  function initCanvas(){
    if (fabricCanvas){ fabricCanvas.clear(); fabricCanvas.dispose(); }
    const canvasElement = document.getElementById("mockupCanvas");
    if (!canvasElement){ console.error("<canvas> no encontrado"); return null; }

    const fixedWidth = 800, fixedHeight = 600;
    canvasElement.width  = fixedWidth;
    canvasElement.height = fixedHeight;
    canvasElement.style.width  = fixedWidth  + "px";
    canvasElement.style.height = fixedHeight + "px";

    fabricCanvas = new fabric.Canvas("mockupCanvas", {
      width: fixedWidth,
      height: fixedHeight,
      preserveObjectStacking: true,
      selection: false
    });
    window.fabricCanvas = fabricCanvas;
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
  function limitarMovimientoCover(img, area, canvas){
    if(!img || !area) return;
    const bound = img.getBoundingRect(true,true);
    const minLeft = area.x + area.width  - bound.width ;
    const maxLeft = area.x;
    const minTop  = area.y + area.height - bound.height;
    const maxTop  = area.y;
    if(bound.width < area.width){ img.scaleX *= area.width / bound.width; img.scaleY = img.scaleX; }
    if(bound.height< area.height){ img.scaleX *= area.height/ bound.height; img.scaleY = img.scaleX; }
    if(img.left < minLeft) img.left = minLeft;
    if(img.left > maxLeft) img.left = maxLeft;
    if(img.top  < minTop ) img.top  = minTop;
    if(img.top  > maxTop ) img.top  = maxTop;
  }
  function setupZoomControls(){
    if(!zoomSlider) return;
    zoomSlider.disabled=false;
    zoomSlider.addEventListener('input',()=>{
      const z = Number(zoomSlider.value);
      if(userImg){ userImg.scaleX = userImg.scaleY = z; limitarMovimientoCover(userImg, areaObj, fabricCanvas); fabricCanvas.renderAll(); }
    });
    if(zoomIn)  zoomIn.onclick=()=>{ zoomSlider.value = Math.min( +zoomSlider.value + 0.1, 3).toFixed(2); zoomSlider.dispatchEvent(new Event('input')); };
    if(zoomOut) zoomOut.onclick=()=>{ zoomSlider.value = Math.max( +zoomSlider.value - 0.1, 0.1).toFixed(2); zoomSlider.dispatchEvent(new Event('input')); };
  }

  /* ---------------------- DPI helpers / tamaño variación ---------------------- */
  function obtenerTamañoProducto(){
    const sel=document.querySelector('select[name^="attribute_"][name*="tamano" i]');
    if(sel&&sel.value){ const m=sel.value.match(/(\d+)\s*[x×]\s*(\d+)/i); if(m) return `${m[1]}x${m[2]}`; }
    return "30x30";
  }
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
    // Verificar que tenemos una imagen para guardar
    if (!userImg) {
      console.error('Error: No hay imagen para guardar');
      return Promise.reject('No hay imagen para guardar');
    }
    
    // Verificar que tenemos un canvas válido
    if (!fabricCanvas) {
      console.error('Error: Canvas no inicializado');
      return Promise.reject('Error interno: Canvas no disponible');
    }
    
    try {
      // Generar imagen final con mejor calidad y formato png
      const finalImg = fabricCanvas.toDataURL({
        format: 'png',
        quality: 1,
        multiplier: 2 // Mejor resolución
      });
      
      // Crear objeto con estado completo de la imagen
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
        },
        // Añadir metadata para debugging
        meta: {
          timestamp: new Date().toISOString(),
          userAgent: navigator.userAgent,
          screenWidth: window.innerWidth
        }
      };
      
      console.log('Enviando datos para guardar personalización');
      
      // Retornar promesa con mejor manejo de errores
      return new Promise((resolve, reject) => {
        jQuery.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'save_personalization',
            security: nonce,
            product_id: productId,
            image_data: finalImg,
            image_state: JSON.stringify(imageState)
          },
          dataType: 'json',
          success: function(response) {
            if (response && response.success) {
              console.log('Personalización guardada correctamente', response.data);
              
              // Si hay ID de personalización, guardarlo en el campo oculto
              if (response.data && response.data.id && personalizacionIdField) {
                personalizacionIdField.value = response.data.id;
              }
              
              resolve(response.data);
            } else {
              console.error('Error en respuesta del servidor:', response);
              reject(response ? response.data : 'Error desconocido del servidor');
            }
          },
          error: function(xhr, status, error) {
            console.error('Error AJAX al guardar:', status, error);
            console.log('Detalles del error:', xhr.responseText);
            reject('Error al comunicarse con el servidor. Por favor, intenta nuevamente.');
          }
        });
      });
    } catch (error) {
      console.error('Error al preparar datos para guardar:', error);
      return Promise.reject('Error interno al procesar la imagen');
    }
  }
  

  /* --------------------------- Personalización previa ------------------------- */
  function cargarPersonalizacion(extra={}){
    return jQuery.post(ajaxUrl,{ action:'load_personalization', product_id:productId, security:nonce, ...extra });
  }
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
  function colocarImagenEnArea(img) {
    // Calcular centro del área editable
    const cx = areaObj.x + areaObj.width / 2;
    const cy = areaObj.y + areaObj.height / 2;
    
    // Calcular escala para que la imagen cubra completamente el área
    // Usamos un factor de 1.05 para asegurar que cubra bien sin excederse demasiado
    const scaleX = (areaObj.width / img.width) * 1.05;
    const scaleY = (areaObj.height / img.height) * 1.05;
    
    // Usar la escala mayor para mantener proporción y asegurar cobertura
    const scale = Math.max(scaleX, scaleY);
    
    // Aplicar transformaciones
    img.set({
      left: cx,
      top: cy,
      scaleX: scale,
      scaleY: scale,
      originX: 'center',
      originY: 'center'
    });
    
    // Aplicar clipPath (recorte) al área editable
    img.clipPath = new fabric.Rect({
      left: areaObj.x,
      top: areaObj.y,
      width: areaObj.width,
      height: areaObj.height,
      absolutePositioned: true
    });
    
    // Forzar actualización del canvas
    fabricCanvas.renderAll();
  }

  function setupImageControls(img){
    img.setControlsVisibility({ mt:false,mb:false,ml:false,mr:false, bl:false,br:false,tl:false,tr:false, mtr:true });
    [ 'moving','rotating','scaling','modified' ].forEach(evt=> img.on(evt,()=>limitarMovimientoCover(img,areaObj,fabricCanvas)) );
    fabricCanvas.setActiveObject(img);
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
  if(inputFoto){ inputFoto.addEventListener('change',e=>{
    const file=e.target.files[0]; if(!file) return; if(fileLabel) fileLabel.textContent=file.name;
    const reader=new FileReader(); reader.onload=ev=>{
      const imgTemp=new Image(); imgTemp.onload=()=>{
        if(!verificarResolucion(imgTemp)){ ocultarCargando(); return; }
        fabric.Image.fromURL(ev.target.result,fbImg=>{
          if(userImg) fabricCanvas.remove(userImg);
          userImg=fbImg; setupImageControls(userImg); fabricCanvas.add(userImg); colocarImagenEnArea(userImg); setupZoomControls(); fabricCanvas.renderAll(); ocultarCargando();
        },{ crossOrigin:'anonymous' });
      }; imgTemp.src=ev.target.result; mostrarCargando();
    }; reader.readAsDataURL(file);
  }); }

  /* ---------------------------- Rotar 90° rápido ------------------------------ */
  if(rotateBtn){ rotateBtn.onclick=()=>{ if(userImg){ userImg.angle=(userImg.angle+90)%360; limitarMovimientoCover(userImg,areaObj,fabricCanvas); fabricCanvas.renderAll(); } } }

  /* ------------------------------- Confirmar ------------------------------- */
  document.addEventListener('click', e => {
    if (!e.target.matches('#confirmar-personalizacion')) return;
    
    // Verificar que hay una imagen subida
    if (!userImg) { 
      alert('Por favor, sube una imagen primero.'); 
      return; 
    }
    
    // Mostrar indicador de carga
    mostrarCargando();
    
    // Guardar con manejo detallado de errores
    guardarPersonalizacion()
      .then(data => {
        // Guardar datos en campo oculto para el carrito
        if (inputHidden && data.image_data) {
          inputHidden.value = data.image_data;
        }
        
        // Guardar ID en campo oculto (si existe)
        if (personalizacionIdField && data.id) {
          personalizacionIdField.value = data.id;
        }
        
        // Actualizar apariencia del botón personalizar
        if (openBtn) {
          openBtn.classList.add('personalizado');
          openBtn.textContent = 'Editar personalización';
        }
        
        // Cerrar popup y mostrar notificación
        popup.style.display = 'none';
        ocultarCargando();
        mostrarNotificacion('¡Tu personalización está lista!');
        
        // Habilitar botón de agregar al carrito
        const addToCartBtn = document.querySelector('.single_add_to_cart_button');
        if (addToCartBtn) {
          addToCartBtn.disabled = false;
          addToCartBtn.classList.remove('disabled');
        }
      })
      .catch(error => {
        ocultarCargando();
        
        // Mensaje de error más detallado
        console.error('Error al guardar personalización:', error);
        
        // Mostrar mensaje de error específico o genérico
        const errorMsg = typeof error === 'string' ? error : 'Error al guardar la personalización. Por favor, intenta nuevamente.';
        
        // Crear un mensaje de error más visible en lugar de un alert
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<strong>Error:</strong> ${errorMsg}`;
        
        // Insertar en la columna derecha
        const rightCol = document.querySelector('.col-right');
        if (rightCol) {
          // Eliminar mensajes previos
          const prevError = rightCol.querySelector('.error-message');
          if (prevError) prevError.remove();
          
          // Insertar al inicio
          rightCol.insertBefore(errorDiv, rightCol.firstChild);
        } else {
          // Fallback a alert si no encontramos donde mostrar el error
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
});