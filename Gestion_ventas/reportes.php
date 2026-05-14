import React, { useState, useMemo, useRef, useEffect } from 'react';
import { 
  Search, Plus, Book, Star, Info, ChevronRight, X, GraduationCap, 
  Sparkles, Trash2, Save, Loader2, Menu, LayoutGrid, Coffee, Plane, 
  Briefcase, Heart, Cpu, Home, Tag, Camera, RefreshCw, AlertCircle, 
  ChevronLeft, Upload
} from 'lucide-react';

const apiKey = "";
const MODEL_NAME = "gemini-2.5-flash-preview-09-2025";

const CATEGORIES = [
  { id: 'all', name: 'Todos', icon: <LayoutGrid size={18} /> },
  { id: 'food', name: 'Comida', icon: <Coffee size={18} /> },
  { id: 'travel', name: 'Viajes', icon: <Plane size={18} /> },
  { id: 'business', name: 'Negocios', icon: <Briefcase size={18} /> },
  { id: 'tech', name: 'Tecnología', icon: <Cpu size={18} /> },
  { id: 'health', name: 'Salud', icon: <Heart size={18} /> },
  { id: 'home', name: 'Hogar', icon: <Home size={18} /> },
  { id: 'other', name: 'Otros', icon: <Tag size={18} /> },
];

export default function App() {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedWord, setSelectedWord] = useState(null);
  const [activeTab, setActiveTab] = useState('dictionary');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [isAdding, setIsAdding] = useState(false);
  const [loadingAI, setLoadingAI] = useState(false);
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);
  const [errorMsg, setErrorMsg] = useState(null);
  
  const videoRef = useRef(null);
  const canvasRef = useRef(null);
  const fileInputRef = useRef(null);

  const [cameraActive, setCameraActive] = useState(false);
  const [ocrResult, setOcrResult] = useState(null);

  const [newWord, setNewWord] = useState({ 
    en: '', es: '', explanation: '', example: '', category: 'other' 
  });
  
  const [dictionary, setDictionary] = useState([
    { id: '1', en: "Enchanting", es: "Encantador", explanation: "Algo que es deliciosamente atractivo o encantador.", example: "The atmosphere of the room was enchanting.", category: 'other' },
    { id: '2', en: "Breakthrough", es: "Avance", explanation: "Un descubrimiento importante que supera un obstáculo.", example: "This is a major breakthrough in science.", category: 'tech' },
  ]);

  // --- FUNCIONES IA ---
  const generateFullContent = async () => {
    if (!newWord.en) return;
    setLoadingAI(true);
    try {
      const prompt = `Actúa como profesor de inglés. Para la palabra "${newWord.en}", genera: 1. Traducción al español. 2. Explicación breve de uso. 3. Un ejemplo en inglés con su traducción. Responde en JSON: {"es": "...", "explanation": "...", "example": "..."}`;
      const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/${MODEL_NAME}:generateContent?key=${apiKey}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          contents: [{ parts: [{ text: prompt }] }],
          generationConfig: { responseMimeType: "application/json" }
        })
      });
      const data = await response.json();
      const result = JSON.parse(data.candidates[0].content.parts[0].text);
      setNewWord(prev => ({ ...prev, ...result }));
    } catch (e) {
      setErrorMsg("Error al obtener datos de la IA");
    } finally {
      setLoadingAI(false);
    }
  };

  const processImageIA = async (base64Data) => {
    setLoadingAI(true);
    try {
      const prompt = "Analiza la imagen. Detecta el texto en inglés. Tradúcelo y extrae 3 palabras clave con su traducción, explicación y ejemplo. Formato JSON: { 'original': '...', 'translation': '...', 'words': [{'en': '...', 'es': '...', 'explanation': '...', 'example': '...'}] }";
      const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/${MODEL_NAME}:generateContent?key=${apiKey}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          contents: [{ parts: [{ text: prompt }, { inlineData: { mimeType: "image/png", data: base64Data } }] }],
          generationConfig: { responseMimeType: "application/json" }
        })
      });
      const data = await response.json();
      setOcrResult(JSON.parse(data.candidates[0].content.parts[0].text));
    } catch (e) { 
      setErrorMsg("Error procesando la imagen");
    } finally { 
      setLoadingAI(false); 
    }
  };

  // --- LÓGICA DE CÁMARA ---
  const startCamera = async () => {
    setCameraActive(true);
    setOcrResult(null);
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
      if (videoRef.current) videoRef.current.srcObject = stream;
    } catch (err) { setErrorMsg("No se pudo acceder a la cámara"); }
  };

  const stopCamera = () => {
    if (videoRef.current && videoRef.current.srcObject) {
      videoRef.current.srcObject.getTracks().forEach(t => t.stop());
    }
    setCameraActive(false);
  };

  const capturePhoto = async () => {
    const canvas = canvasRef.current;
    const video = videoRef.current;
    if (!video) return;
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const base64 = canvas.toDataURL('image/png').split(',')[1];
    stopCamera();
    await processImageIA(base64);
  };

  const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = async (event) => {
      const base64 = event.target.result.split(',')[1];
      setOcrResult(null);
      await processImageIA(base64);
    };
    reader.readAsDataURL(file);
  };

  const handleSaveWord = (wordData) => {
    setDictionary(prev => [{ ...wordData, id: Date.now().toString() }, ...prev]);
    setIsAdding(false);
    setNewWord({ en: '', es: '', explanation: '', example: '', category: 'other' });
  };

  const filteredWords = useMemo(() => {
    return dictionary.filter(w => {
      const matchesSearch = w.en.toLowerCase().includes(searchTerm.toLowerCase()) || w.es.toLowerCase().includes(searchTerm.toLowerCase());
      const matchesCat = selectedCategory === 'all' || w.category === selectedCategory;
      return matchesSearch && matchesCat;
    });
  }, [searchTerm, dictionary, selectedCategory]);

  return (
    <div className="flex h-screen bg-[#0a0a0c] text-slate-100 overflow-hidden font-sans">
      
      {/* Sidebar Colapsable */}
      <aside className={`${isSidebarOpen ? 'w-64' : 'w-20'} bg-[#121217] border-r border-white/5 transition-all duration-300 flex flex-col z-50`}>
        <div className="p-6 flex items-center gap-3">
          {/* Nuevo Icono de Libro Inteligente */}
          <div className="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/30">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="w-6 h-6 text-indigo-400">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
              <circle cx="12" cy="10" r="2" fill="currentColor" opacity="0.3" />
              <path d="m15 7 2-2" opacity="0.6" />
            </svg>
          </div>
          {isSidebarOpen && <h1 className="text-2xl font-bold tracking-tighter italic font-serif bg-gradient-to-r from-white to-slate-500 bg-clip-text text-transparent">DEKS</h1>}
        </div>

        <nav className="flex-1 px-3 space-y-1 mt-4 overflow-y-auto custom-scrollbar">
          <button onClick={() => setActiveTab('dictionary')} className={`w-full flex items-center gap-3 p-3 rounded-xl transition-all ${activeTab === 'dictionary' ? 'bg-white/10 text-white' : 'text-slate-500 hover:bg-white/5'}`}>
            <Book size={20} /> {isSidebarOpen && <span className="font-semibold text-sm">Biblioteca</span>}
          </button>
          <button onClick={() => setActiveTab('camera')} className={`w-full flex items-center gap-3 p-3 rounded-xl transition-all ${activeTab === 'camera' ? 'bg-white/10 text-white' : 'text-slate-500 hover:bg-white/5'}`}>
            <Camera size={20} /> {isSidebarOpen && <span className="font-semibold text-sm">Escáner IA</span>}
          </button>
          
          <div className="h-px bg-white/5 my-4 mx-2" />
          
          {CATEGORIES.map(cat => (
            <button key={cat.id} onClick={() => { setSelectedCategory(cat.id); setActiveTab('dictionary'); }} className={`w-full flex items-center gap-3 p-3 rounded-xl transition-all ${selectedCategory === cat.id && activeTab === 'dictionary' ? 'bg-white/5 text-white' : 'text-slate-500 hover:bg-white/5'}`}>
              <span className={selectedCategory === cat.id ? "text-indigo-400" : ""}>{cat.icon}</span>
              {isSidebarOpen && <span className="text-sm font-medium">{cat.name}</span>}
            </button>
          ))}
        </nav>

        <div className="p-4 border-t border-white/5">
          <button 
            onClick={() => setIsSidebarOpen(!isSidebarOpen)} 
            className="w-full flex justify-center p-2 text-slate-500 hover:text-white transition-colors bg-white/5 rounded-lg hover:bg-white/10"
          >
            {isSidebarOpen ? <ChevronLeft size={20} /> : <Menu size={20} />}
          </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <div className="flex-1 flex flex-col min-w-0">
        <header className="h-20 px-8 flex justify-between items-center border-b border-white/5 bg-[#0a0a0c]">
          <h2 className="text-xs font-bold text-slate-500 uppercase tracking-widest">
            {activeTab === 'camera' ? 'Neural Scan' : 'Diccionario Personal'}
          </h2>
          <button onClick={() => setIsAdding(true)} className="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl font-bold text-xs flex items-center gap-2 transition-all active:scale-95 shadow-lg shadow-indigo-600/20">
            <Plus size={18} /> AGREGAR
          </button>
        </header>

        <main className="flex-1 overflow-y-auto p-8 custom-scrollbar">
          {activeTab === 'dictionary' ? (
            <div className="max-w-5xl mx-auto space-y-8">
              <div className="relative">
                <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" size={20} />
                <input 
                  className="w-full bg-[#121217] border border-white/5 rounded-2xl pl-12 pr-6 py-4 text-white outline-none focus:border-indigo-500 transition-all shadow-xl"
                  placeholder="Buscar palabra..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {filteredWords.map(word => (
                  <div key={word.id} onClick={() => setSelectedWord(word)} className="bg-[#121217] p-6 rounded-2xl border border-white/5 hover:border-indigo-500/50 transition-all cursor-pointer group hover:shadow-2xl hover:shadow-indigo-500/5">
                    <div className="flex justify-between mb-4">
                      <span className="text-[10px] font-bold bg-white/5 px-2 py-1 rounded uppercase tracking-tighter text-slate-400">{CATEGORIES.find(c => c.id === word.category).name}</span>
                      <button onClick={(e) => {e.stopPropagation(); setDictionary(prev => prev.filter(w => w.id !== word.id));}} className="opacity-0 group-hover:opacity-100 p-1 hover:text-red-500 transition-all">
                        <Trash2 size={16} />
                      </button>
                    </div>
                    <h3 className="text-xl font-bold text-white mb-1 group-hover:text-indigo-400 transition-colors">{word.en}</h3>
                    <p className="text-slate-500 text-sm italic line-clamp-1">{word.es}</p>
                  </div>
                ))}
              </div>
            </div>
          ) : (
            <div className="max-w-3xl mx-auto space-y-6">
              {!cameraActive && !ocrResult && !loadingAI && (
                <div className="bg-[#121217] border border-dashed border-white/10 rounded-3xl p-16 text-center space-y-6">
                  <div className="w-16 h-16 bg-indigo-600/20 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto">
                    <Camera size={32} />
                  </div>
                  <h3 className="text-xl font-bold">Escáner de Texto IA</h3>
                  <div className="flex gap-4 justify-center">
                    <button onClick={startCamera} className="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-indigo-600/20">
                      <Camera size={18} /> USAR CÁMARA
                    </button>
                    <button onClick={() => fileInputRef.current?.click()} className="bg-white/5 hover:bg-white/10 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 border border-white/10">
                      <Upload size={18} /> SUBIR IMAGEN
                    </button>
                    <input type="file" ref={fileInputRef} className="hidden" accept="image/*" onChange={handleFileUpload} />
                  </div>
                </div>
              )}

              {cameraActive && (
                <div className="relative rounded-3xl overflow-hidden bg-black aspect-video border border-white/10 shadow-2xl">
                  <video ref={videoRef} autoPlay playsInline className="w-full h-full object-cover" />
                  <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-4">
                    <button onClick={stopCamera} className="p-4 bg-red-600/20 backdrop-blur-md rounded-full text-red-500 border border-red-500/20 hover:bg-red-600 hover:text-white transition-all"><X size={24} /></button>
                    <button onClick={capturePhoto} className="p-4 bg-white rounded-full text-black shadow-2xl active:scale-90 transition-all"><Camera size={24} /></button>
                  </div>
                </div>
              )}

              {loadingAI && (
                <div className="text-center py-20 space-y-4">
                  <Loader2 size={40} className="text-indigo-500 animate-spin mx-auto" />
                  <p className="text-sm font-bold text-indigo-400 tracking-widest animate-pulse">PROCESANDO CEREBRO IA...</p>
                </div>
              )}

              {ocrResult && (
                <div className="space-y-6 animate-in slide-in-from-bottom-4">
                  <div className="bg-[#121217] p-8 rounded-3xl border border-white/10 space-y-4 shadow-xl">
                    <span className="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Texto Original</span>
                    <p className="text-2xl font-bold leading-tight">{ocrResult.original}</p>
                    <div className="h-px bg-white/5" />
                    <span className="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Traducción</span>
                    <p className="text-xl italic text-slate-300">{ocrResult.translation}</p>
                  </div>

                  <div className="grid gap-3">
                    {ocrResult.words.map((w, i) => (
                      <div key={i} className="bg-[#121217] p-5 rounded-2xl border border-white/5 flex justify-between items-center group hover:border-indigo-500/30 transition-all shadow-md">
                        <div>
                          <h5 className="font-bold text-lg text-white">{w.en}</h5>
                          <p className="text-slate-500 text-sm italic">{w.es}</p>
                        </div>
                        <button onClick={() => handleSaveWord({...w, category: 'other'})} className="p-2 bg-indigo-600/10 text-indigo-500 rounded-lg hover:bg-indigo-600 hover:text-white transition-all"><Plus size={20} /></button>
                      </div>
                    ))}
                  </div>
                  <button onClick={() => setOcrResult(null)} className="w-full py-4 bg-white/5 rounded-xl text-slate-500 font-bold hover:text-white transition-all border border-white/5">NUEVO ESCANEO</button>
                </div>
              )}
            </div>
          )}
        </main>
      </div>

      <canvas ref={canvasRef} className="hidden" />

      {/* Modal Agregar: Corregido con Scroll y altura máxima */}
      {isAdding && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
          <div className="bg-[#121217] w-full max-w-lg max-h-[90vh] rounded-3xl border border-white/10 shadow-2xl animate-in zoom-in-95 flex flex-col">
            <div className="p-6 border-b border-white/5 flex justify-between items-center flex-shrink-0">
              <h2 className="font-bold text-lg">Nuevo Término</h2>
              <button onClick={() => setIsAdding(false)} className="text-slate-500 hover:text-white p-2 transition-colors"><X size={20} /></button>
            </div>
            {/* Contenedor de inputs con SCROLL */}
            <div className="p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-1.5">
                   <label className="text-[10px] font-bold text-slate-600 uppercase ml-1">Inglés</label>
                   <input className="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white outline-none focus:border-indigo-500 transition-all" value={newWord.en} onChange={e => setNewWord({...newWord, en: e.target.value})} placeholder="Word" />
                </div>
                <div className="space-y-1.5">
                   <label className="text-[10px] font-bold text-slate-600 uppercase ml-1">Español</label>
                   <input className="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white outline-none focus:border-indigo-500 transition-all" value={newWord.es} onChange={e => setNewWord({...newWord, es: e.target.value})} placeholder="Traducción" />
                </div>
              </div>
              <button onClick={generateFullContent} disabled={loadingAI || !newWord.en} className="w-full py-3 bg-indigo-600/10 text-indigo-500 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-600 hover:text-white transition-all border border-indigo-500/20">
                {loadingAI ? <Loader2 className="animate-spin" size={16} /> : <Sparkles size={16} />} LLENAR CON IA
              </button>
              <div className="space-y-1.5">
                <label className="text-[10px] font-bold text-slate-600 uppercase ml-1">Explicación</label>
                <textarea className="w-full bg-white/5 border border-white/10 rounded-xl p-3 h-24 text-white outline-none focus:border-indigo-500 resize-none transition-all" value={newWord.explanation} onChange={e => setNewWord({...newWord, explanation: e.target.value})} placeholder="..." />
              </div>
              <div className="space-y-1.5">
                <label className="text-[10px] font-bold text-slate-600 uppercase ml-1">Ejemplo Contextual</label>
                <textarea className="w-full bg-white/5 border border-white/10 rounded-xl p-3 h-20 text-white outline-none focus:border-indigo-500 resize-none italic transition-all" value={newWord.example} onChange={e => setNewWord({...newWord, example: e.target.value})} placeholder="..." />
              </div>
            </div>
            {/* Botón guardar fijo al final del modal */}
            <div className="p-6 border-t border-white/5 flex-shrink-0">
              <button onClick={() => handleSaveWord(newWord)} className="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-all active:scale-[0.98]">GUARDAR EN DICCIONARIO</button>
            </div>
          </div>
        </div>
      )}

      {/* Detalle */}
      {selectedWord && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md animate-in fade-in">
          <div className="bg-[#121217] w-full max-w-lg rounded-[2rem] border border-white/10 overflow-hidden shadow-[0_0_100px_rgba(79,70,229,0.1)]">
            <div className="bg-indigo-600 p-10 text-white relative">
              <button onClick={() => setSelectedWord(null)} className="absolute top-6 right-6 p-2 text-white/50 hover:text-white transition-all"><X size={24} /></button>
              <h2 className="text-4xl font-bold mb-1 leading-tight">{selectedWord.en}</h2>
              <p className="text-indigo-200 text-lg italic opacity-80">{selectedWord.es}</p>
            </div>
            <div className="p-10 space-y-8">
              <div className="space-y-3">
                <h4 className="text-[10px] font-bold text-indigo-400 uppercase tracking-widest flex items-center gap-2"><Info size={14} /> Significado</h4>
                <p className="text-slate-300 leading-relaxed text-lg">{selectedWord.explanation}</p>
              </div>
              <div className="space-y-3">
                <h4 className="text-[10px] font-bold text-indigo-400 uppercase tracking-widest flex items-center gap-2"><Star size={14} /> Ejemplo en vivo</h4>
                <div className="bg-white/5 p-6 rounded-2xl italic text-white leading-relaxed border-l-2 border-indigo-500">
                   "{selectedWord.example}"
                </div>
              </div>
              <button onClick={() => setSelectedWord(null)} className="w-full py-4 bg-white/5 rounded-xl font-bold transition-all border border-white/5 hover:bg-white/10">CERRAR DETALLES</button>
            </div>
          </div>
        </div>
      )}

      {errorMsg && (
        <div className="fixed bottom-6 right-6 z-[110] bg-red-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-2 animate-in slide-in-from-right-10 border border-white/20">
          <AlertCircle size={18} /> {errorMsg}
        </div>
      )}

      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.1); }
      `}</style>
    </div>
  );
}
