document.addEventListener("DOMContentLoaded", function () {
    let canvas = new fabric.Canvas("mockupCanvas");

    // mockupData => { url: '...', area: '...' }
    let mockupUrl = typeof mockupData !== "undefined" ? mockupData.url : "";
    fabric.Image.fromURL(mockupUrl, function (img) {
        img.scaleToWidth(500);
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
    });
});
