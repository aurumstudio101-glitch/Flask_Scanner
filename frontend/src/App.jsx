import React, { useState, useEffect } from 'react';
import { 
  CheckCircle, 
  AlertCircle, 
  LayoutDashboard, 
  History, 
  Settings, 
  LogOut,
  ChevronRight,
  Maximize2,
  FileText,
  User,
  Building,
  Save,
  Loader2
} from 'lucide-react';

function App() {
  const [selectedField, setSelectedField] = useState('name');
  const [isSaving, setIsSaving] = useState(false);
  const [showSuccess, setShowSuccess] = useState(false);
  const [formData, setFormData] = useState({
    receiptNo: '12894',
    irNo: '16312',
    customerName: 'M.D. Nisal Sayuranga',
    nic: '200021301452',
    principal: '45,000.00',
    branch: 'Kiribathgoda'
  });

  const handleInputChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleVerify = () => {
    setIsSaving(true);
    // Simulate API Call
    setTimeout(() => {
      setIsSaving(false);
      setShowSuccess(true);
      setTimeout(() => setShowSuccess(false), 3000);
    }, 1000);
  };

  return (
    <div className="flex w-full h-screen bg-[#0b0f1a] text-slate-200 overflow-hidden">
      
      {/* Sidebar */}
      <aside className="w-72 bg-[#0f172a] border-r border-slate-800 flex flex-col shrink-0">
        <div className="p-8">
          <div className="flex items-center gap-3 mb-10">
            <div className="w-12 h-12 bg-sky-500 rounded-2xl flex items-center justify-center shadow-lg shadow-sky-500/20">
              <Building className="text-white" size={28} />
            </div>
            <div>
              <h1 className="font-bold text-xl text-white">Flask Scanner</h1>
              <span className="text-[10px] text-sky-400 font-bold tracking-widest uppercase">Pro Edition</span>
            </div>
          </div>

          <nav className="space-y-2">
            <button className="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold bg-sky-500 text-white shadow-lg shadow-sky-500/20">
              <LayoutDashboard size={20} />
              <span className="text-sm">Dashboard</span>
            </button>
            <button className="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-800/50 hover:text-slate-300 transition-all">
              <History size={20} />
              <span className="text-sm">Batch List</span>
            </button>
            <button className="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-800/50 hover:text-slate-300 transition-all">
              <Settings size={20} />
              <span className="text-sm">Settings</span>
            </button>
          </nav>
        </div>

        <div className="mt-auto p-8 border-t border-slate-800/50">
          <button 
            onClick={() => alert('Signing out...')}
            className="flex items-center gap-3 text-slate-500 hover:text-red-400 transition-colors w-full"
          >
            <LogOut size={20} />
            <span className="font-bold text-sm text-left">Logout System</span>
          </button>
        </div>
      </aside>

      {/* Main Workspace */}
      <main className="flex-1 flex flex-col min-w-0">
        
        {/* Top Header */}
        <header className="h-20 border-b border-slate-800/50 flex items-center justify-between px-10 bg-[#0f172a]/50 backdrop-blur-md">
          <div className="flex items-center gap-3">
            <span className="text-slate-500 text-sm">Batch Verifier</span>
            <ChevronRight size={14} className="text-slate-700" />
            <span className="text-sky-400 font-bold text-sm">#8291_KIRI_2026</span>
          </div>
          <div className="flex items-center gap-6">
            <div className="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 rounded-xl">
              <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
              <span className="text-xs font-bold text-emerald-400 tracking-wider">LIVE DATA SYNC</span>
            </div>
            <div className="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-700">
              <User size={20} />
            </div>
          </div>
        </header>

        {/* Content Area */}
        <div className="flex-1 flex overflow-hidden">
          
          {/* Document Viewer */}
          <div className="flex-1 p-8 overflow-hidden flex flex-col">
            <div className="mb-6 flex justify-between items-center">
              <div className="flex gap-4">
                <span className="text-xs font-black text-slate-500 uppercase tracking-widest bg-slate-800 px-3 py-1 rounded">Doc ID: 12894</span>
                <span className="text-xs font-black text-sky-500 uppercase tracking-widest bg-sky-500/10 px-3 py-1 rounded">Primary Receipt</span>
              </div>
              <button className="p-2 bg-slate-800 rounded-lg hover:bg-slate-700 text-slate-400">
                <Maximize2 size={18} />
              </button>
            </div>

            <div className="flex-1 bg-[#111827] rounded-[2rem] border border-slate-800 flex items-center justify-center p-10 relative overflow-hidden shadow-2xl">
              <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:20px_20px]"></div>
              
              <div className="bg-white w-full max-w-[450px] aspect-[1/1.4] rounded shadow-2xl relative overflow-hidden transition-transform duration-300 hover:scale-[1.01]">
                <div className="p-10 text-slate-900 font-serif">
                  <div className="text-center mb-10 border-b border-slate-100 pb-6">
                    <h2 className="text-2xl font-black uppercase">Rupasinghe Trust</h2>
                    <p className="text-[10px] text-slate-400 font-sans tracking-[0.2em] font-bold">Investments • Kiribathgoda</p>
                  </div>
                  <div className="space-y-6 text-sm font-sans">
                    <div className="flex justify-between border-b border-slate-50 pb-2">
                      <span className="text-slate-400 font-bold uppercase text-[10px]">Receipt No</span>
                      <span className="font-black text-lg">12894</span>
                    </div>
                    <div className="flex justify-between border-b border-slate-50 pb-2">
                      <span className="text-slate-400 font-bold uppercase text-[10px]">Reference IR</span>
                      <span className="font-black text-lg text-sky-600">16312</span>
                    </div>
                    <div className="pt-6 relative">
                      <span className="text-slate-400 font-bold uppercase text-[10px] block mb-2">Customer Name</span>
                      <div className="text-xl font-bold bg-slate-50 p-3 border border-slate-100 rounded">
                        {formData.customerName}
                      </div>
                      {selectedField === 'name' && (
                        <div className="absolute inset-x-0 -inset-y-2 border-2 border-sky-500 bg-sky-500/5 rounded animate-pulse pointer-events-none"></div>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Verification Panel */}
          <div className="w-[480px] border-l border-slate-800/50 bg-[#0f172a] p-10 flex flex-col">
            <div className="mb-10">
              <div className="flex items-center gap-3 mb-3">
                <AlertCircle className="text-amber-500" size={24} />
                <h3 className="text-xl font-black text-white">Verifier Panel</h3>
              </div>
              <p className="text-slate-500 text-sm">Please update or confirm the fields extracted by AI.</p>
            </div>

            <div className="flex-1 space-y-6 overflow-y-auto pr-2">
              <InputField 
                label="Receipt Number" 
                value={formData.receiptNo} 
                onChange={(val) => handleInputChange('receiptNo', val)}
                onFocus={() => setSelectedField('receipt')}
              />
              <InputField 
                label="IR / R Number" 
                value={formData.irNo} 
                onChange={(val) => handleInputChange('irNo', val)}
                onFocus={() => setSelectedField('ir')}
              />
              <InputField 
                label="Customer Name" 
                value={formData.customerName} 
                onChange={(val) => handleInputChange('customerName', val)}
                onFocus={() => setSelectedField('name')}
                active={selectedField === 'name'}
              />
              <InputField 
                label="NIC Number" 
                value={formData.nic} 
                onChange={(val) => handleInputChange('nic', val)}
                onFocus={() => setSelectedField('nic')}
              />
              <div className="grid grid-cols-2 gap-4">
                <InputField label="Principal (Rs)" value={formData.principal} onChange={(v) => handleInputChange('principal', v)} />
                <InputField label="Branch" value={formData.branch} onChange={(v) => handleInputChange('branch', v)} />
              </div>
            </div>

            <div className="pt-10 space-y-3">
              <button 
                onClick={handleVerify}
                disabled={isSaving}
                className="w-full bg-sky-500 hover:bg-sky-400 disabled:bg-slate-700 text-white font-black py-5 rounded-2xl flex items-center justify-center gap-3 transition-all shadow-xl shadow-sky-500/20"
              >
                {isSaving ? <Loader2 className="animate-spin" /> : <CheckCircle size={22} />}
                {isSaving ? 'UPDATING...' : 'VERIFY & SAVE'}
              </button>
            </div>
          </div>
        </div>
      </main>

      {/* Success Notification */}
      {showSuccess && (
        <div className="fixed bottom-10 right-10 bg-emerald-500 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-bounce">
          <CheckCircle size={24} />
          <span className="font-bold">Record Saved Successfully!</span>
        </div>
      )}
    </div>
  );
}

function InputField({ label, value, onChange, onFocus, active = false }) {
  return (
    <div className="space-y-1.5">
      <label className="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">{label}</label>
      <input 
        type="text" 
        value={value}
        onChange={(e) => onChange(e.target.value)}
        onFocus={onFocus}
        className={`w-full bg-[#1e293b]/40 border-2 ${active ? 'border-sky-500' : 'border-slate-800'} rounded-xl px-5 py-4 text-white focus:outline-none transition-all font-bold`}
      />
    </div>
  );
}

export default App;
