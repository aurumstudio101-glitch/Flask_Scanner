import React, { useState, useCallback } from 'react';
import { Upload, Image as ImageIcon, X, Zap, CheckCircle, AlertCircle } from 'lucide-react';

const SingleScan = () => {
  const [file, setFile] = useState(null);
  const [preview, setPreview] = useState(null);
  const [isProcessing, setIsProcessing] = useState(false);
  const [isDone, setIsDone] = useState(false);

  const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];
    if (selectedFile) {
      setFile(selectedFile);
      setPreview(URL.createObjectURL(selectedFile));
    }
  };

  const startProcessing = () => {
    setIsProcessing(true);
    // Simulate AI processing
    setTimeout(() => {
      setIsProcessing(false);
      setIsDone(true);
    }, 3000);
  };

  const reset = () => {
    setFile(null);
    setPreview(null);
    setIsDone(false);
  };

  return (
    <div className="p-8 max-w-5xl mx-auto space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-slate-900">AI Intelligent Scan</h1>
        <p className="text-slate-500 mt-1">Upload a pawn bill to extract information using Gemini AI.</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Upload Column */}
        <div className="space-y-6">
          <div className={`card p-8 border-2 border-dashed transition-all ${
            preview ? 'border-blue-200 bg-blue-50/30' : 'border-slate-200 hover:border-blue-400 hover:bg-blue-50/10'
          }`}>
            {!preview ? (
              <label className="flex flex-col items-center justify-center gap-4 cursor-pointer py-12">
                <div className="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                  <Upload size={32} />
                </div>
                <div className="text-center">
                  <p className="text-lg font-bold text-slate-900">Drop your bill here</p>
                  <p className="text-slate-500 text-sm">or click to browse files</p>
                </div>
                <input type="file" className="hidden" onChange={handleFileChange} accept="image/*,application/pdf" />
              </label>
            ) : (
              <div className="relative group">
                <img src={preview} alt="Preview" className="w-full rounded-lg shadow-xl border border-white" />
                <button 
                  onClick={reset}
                  className="absolute -top-3 -right-3 w-8 h-8 bg-white text-rose-500 rounded-full shadow-lg flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all"
                >
                  <X size={18} />
                </button>
              </div>
            )}
          </div>

          <div className="flex gap-4">
            <button 
              disabled={!file || isProcessing}
              onClick={startProcessing}
              className={`flex-1 py-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-all ${
                !file || isProcessing 
                ? 'bg-slate-100 text-slate-400 cursor-not-allowed' 
                : 'bg-blue-600 text-white shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-[0.98]'
              }`}
            >
              {isProcessing ? (
                <>
                  <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                  Processing with AI...
                </>
              ) : (
                <>
                  <Zap size={20} />
                  Start AI Extraction
                </>
              )}
            </button>
          </div>
        </div>

        {/* Status Column */}
        <div className="space-y-6">
          <div className="card p-6 h-full flex flex-col">
            <h3 className="font-bold text-lg mb-6 flex items-center gap-2">
              <Zap size={20} className="text-amber-500" />
              Extraction Status
            </h3>

            {!file && (
              <div className="flex-1 flex flex-col items-center justify-center text-center opacity-50">
                <ImageIcon size={64} className="text-slate-300 mb-4" />
                <p className="text-slate-500 font-medium">Waiting for document...</p>
              </div>
            )}

            {file && !isDone && (
              <div className="space-y-4">
                <div className="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                  <div className="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <CheckCircle size={20} />
                  </div>
                  <div>
                    <p className="text-sm font-bold">Image Loaded</p>
                    <p className="text-xs text-slate-500">{file.name}</p>
                  </div>
                </div>
                
                {isProcessing && (
                  <div className="p-4 bg-blue-50 border border-blue-100 rounded-xl animate-pulse">
                    <div className="flex items-center gap-4">
                      <div className="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center">
                        <Zap size={20} />
                      </div>
                      <div>
                        <p className="text-sm font-bold text-blue-900">Gemini AI Working</p>
                        <p className="text-xs text-blue-600">Extracting handwriting & numbers...</p>
                      </div>
                    </div>
                  </div>
                )}
              </div>
            )}

            {isDone && (
              <div className="flex-1 flex flex-col items-center justify-center text-center space-y-4">
                <div className="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                  <CheckCircle size={40} />
                </div>
                <div>
                  <h4 className="text-xl font-bold text-slate-900">Extraction Complete</h4>
                  <p className="text-slate-500 mt-1">Data successfully verified by AI.</p>
                </div>
                <button className="px-6 py-2 bg-slate-900 text-white rounded-lg font-bold hover:bg-slate-800 transition-all">
                  Verify Results
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default SingleScan;
