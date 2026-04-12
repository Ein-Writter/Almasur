particlesJS("particles-js", {
  "particles": {
    "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
    "color": { "value": "#38bdf8" }, // Tu color celeste Sky
    "shape": { "type": "circle" },
    "opacity": { "value": 0.5, "random": false },
    "size": { "value": 3, "random": true },
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#38bdf8",
      "opacity": 0.4,
      "width": 1
    },
    "move": {
      "enable": true,
      "speed": 2,
      "direction": "none",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": { "enable": true, "mode": "grab" }, // Se conectan al pasar el mouse
      "onclick": { "enable": true, "mode": "push" }
    }
  },
  "retina_detect": true
});