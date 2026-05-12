import React, { useState } from 'react';
import { Upload, Play, CheckCircle, Clock, AlertCircle, Trash2, Layers } from 'lucide-react';

const BatchScan = () => {
  const [files, setFiles] = useState([]);
  const [isProcessing, setIsProcessing] = useState(false);

  const handleFiles = (e) => {
    const selectedFiles = Array.from(e.target.files);
    const newFiles = selectedFiles.map(file => ({
      id: Math.random().toString(36).substr(2, 9),
      name: file.name,
      status: 'waiting',
      preview: URL.createObjectURL(file)
    }));
    setFiles([...files, ...newFiles]);
  };

  const removeFile = (id) => {
    setFiles(files.filter(f => f.id !== id));
  };

  const startBatch = () => {
    setIsProcessing(true);
    // Simulate sequential processing
    let current = 0;
    const processNext = () => {
      if (current >= files.length) {
        setIsProcessing(false);
        return;
      }
      
      setFiles(prev => prev.map((f, i) => i === current ? { ...f, status: 'processing' } : f));
      
      setTimeout(() => {
        setFiles(prev => prev.map((f, i) => i === current ? { ...f, status: 'completed' } : f));
        current++;
        processNext();
      }, 1500);
    };
    processNext();
  };

  return (
    <div className="p-8 max-w-6xl mx-auto space-y-8">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-slate-900">Batch Processing</h1>
          <p className="text-slate-500 mt-1">Efficiently scan and digitize multiple bills in one go.</p>
        </div>
        <div className="flex gap-3">
          <button 
            disabled={files.length === 0 || isProcessing}
            onClick={startBatch}
            className={`btn-primary px-8 py-3 rounded-xl shadow-lg transition-all ${
              files.length === 0 || isProcessing ? 'opacity-50 cursor-not-allowed' : 'hover:scale-105 active:scale-95'
            }`}
          >
            <Play size={20} fill="currentColor" />
            Start Batch Scan
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Dropzone & Queue */}
        <div className="lg:col-span-2 space-y-6">
          <div className="card p-10 border-2 border-dashed border-slate-200 bg-white hover:border-blue-400 hover:bg-blue-50/10 transition-all text-center">
            <label className="cursor-pointer flex flex-col items-center gap-4">
              <div className="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <Layers size={32} />
              </div>
              <div>
                <p className="text-lg font-bold">Select Multiple Files</p>
                <p className="text-slate-500 text-sm">Upload up to 50 images at once</p>
              </div>
              <input type="file" multiple className="hidden" onChange={handleFiles} />
            </label>
          </div>

          <div className="space-y-3">
            <h3 className="font-bold text-slate-700 flex justify-between">
              Upload Queue 
              <span className="text-sm font-normal text-slate-500">{files.length} files selected</span>
            </h3>
            <div className="space-y-2">
              {files.map((file) => (
                <div key={file.id} className="card p-4 flex items-center justify-between group">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200">
                      <img src={file.preview} alt="Thumb" className="w-full h-full object-cover" />
                    </div>
                    <div>
                      <p className="text-sm font-bold text-slate-900 truncate w-48">{file.name}</p>
                      <div className="flex items-center gap-2 mt-1">
                        {file.status === 'waiting' && <span className="flex items-center gap-1 text-[10px] text-slate-400 uppercase font-bold"><Clock size={10} /> Waiting</span>}
                        {file.status === 'processing' && <span className="flex items-center gap-1 text-[10px] text-blue-600 uppercase font-bold animate-pulse"><Zap size={10} /> Processing...</span>}
                        {file.status === 'completed' && <span className="flex items-center gap-1 text-[10px] text-emerald-600 uppercase font-bold"><CheckCircle size={10} /> Completed</span>}
                      </div>
                    </div>
                  </div>
                  <button 
                    disabled={isProcessing}
                    onClick={() => removeFile(file.id)}
                    className="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all"
                  >
                    <Trash2 size={18} />
                  </button>
                </div>
              ))}
              {files.length === 0 && (
                <div className="py-12 text-center opacity-30">
                  <Layers size={48} className="mx-auto mb-2" />
                  <p className="font-medium italic">No files in queue</p>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Status & Stats */}
        <div className="space-y-6">
          <div className="card p-6 bg-slate-900 text-white">
            <h3 className="font-bold text-lg mb-6 text-slate-400">Processing Logs</h3>
            <div className="space-y-4 font-mono text-xs">
              <div className="text-emerald-400 flex gap-2">
                <span className="opacity-50">[09:52:10]</span>
                <span>System ready for batch...</span>
              </div>
              {files.filter(f => f.status === 'processing' || f.status === 'completed').map((f, i) => (
                <div key={i} className={f.status === 'completed' ? 'text-blue-300' : 'text-amber-400'}>
                  <span className="opacity-50">[09:52:{15 + i}]</span>
                  <span>{f.status === 'completed' ? 'Success:' : 'Working on:'} {f.name}</span>
                </div>
              ))}
              {isProcessing && (
                <div className="text-blue-400 animate-pulse flex gap-2">
                   <span className="opacity-50">[*]</span>
                   <span>Accessing Gemini API...</span>
                </div>
              )}
            </div>
          </div>

          <div className="card p-6">
            <h3 className="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider">Queue Stats</h3>
            <div className="grid grid-cols-2 gap-4">
              <div className="p-4 bg-slate-50 rounded-xl">
                <p className="text-xs text-slate-500 font-bold uppercase">Success</p>
                <p className="text-2xl font-bold text-emerald-600">{files.filter(f => f.status === 'completed').length}</p>
              </div>
              <div className="p-4 bg-slate-50 rounded-xl">
                <p className="text-xs text-slate-500 font-bold uppercase">Errors</p>
                <p className="text-2xl font-bold text-slate-300">0</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default BatchScan;
