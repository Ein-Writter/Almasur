document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('dragon-canvas');
    const ctx = canvas.getContext('2d');

    // Ajustar el canvas al tamaño de la ventana
    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        ctx.imageSmoothingEnabled = false;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    // Cargar la imagen de la cabeza
    const headImage = new Image();
    headImage.src = 'assets/cabeza-dragon.png';

    // Configuración del Trail
    const numSegments = 25; // segmentos
    const segments = [];
    const headSize = 32; // Tamaño cabeza
    const bodyColor = '#D4AF37'; // color cuerpo

    // Inicializar segmentos en la posición 0,0
    for (let i = 0; i < numSegments; i++) {
        segments.push({ x: 0, y: 0 });
    }

    let mouseX = 0;
    let mouseY = 0;

    // Actualizar posición del ratón
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Bucle de animación
    function animate() {
        // Limpiar el canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Actualizar la posición de los segmentos
        // La cabeza sigue al ratón
        segments[0].x = mouseX;
        segments[0].y = mouseY;

        // Cada segmento sigue al anterior con retraso
        for (let i = 1; i < numSegments; i++) {
            const target = segments[i - 1];
            // Física simple de "arrastre"
            segments[i].x += (target.x - segments[i].x) * 0.3;
            segments[i].y += (target.y - segments[i].y) * 0.3;
        }

        // Dibujar el cuerpo
        for (let i = numSegments - 1; i > 0; i--) {
            const seg = segments[i];
            // El tamaño disminuye hacia la cola
            const size = (1 - i / numSegments) * (headSize * 0.6);
            
            ctx.fillStyle = bodyColor;
            // Dibujamos cuadrados para efecto pixel art
            ctx.fillRect(seg.x - size/2, seg.y - size/2, size, size);
        }

        // Dibujar la cabeza
        // Calculamos ángulo
        const angle = Math.atan2(mouseY - segments[1].y, mouseX - segments[1].x);
        
        ctx.save();
        ctx.translate(mouseX, mouseY);
        ctx.rotate(angle + Math.PI / 2);
        
        if (headImage.complete) {
            ctx.drawImage(headImage, -headSize/2, -headSize/2, headSize, headSize);
        }
        ctx.restore();

        requestAnimationFrame(animate);
    }

    // Iniciar animación
    animate();
});
