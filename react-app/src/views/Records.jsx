import React from 'react';
import { Search, Filter, Download, MoreVertical, Eye, Trash2, CheckCircle } from 'lucide-react';

const Records = () => {
  const records = [
    { id: 1, customer: 'Nisal Sayuranga', ir: 'IR-1029', date: '2026-05-10', amount: '45,000.00', status: 'verified' },
    { id: 2, customer: 'Kamal Perera', ir: 'IR-1030', date: '2026-05-09', amount: '12,500.00', status: 'pending' },
    { id: 3, customer: 'Sunil Silva', ir: 'IR-1031', date: '2026-05-08', amount: '8,000.00', status: 'failed' },
    { id: 4, customer: 'Chaminda Ruwan', ir: 'IR-1032', date: '2026-05-07', amount: '120,000.00', status: 'verified' },
    { id: 5, customer: 'Ruwan Kumara', ir: 'IR-1033', date: '2026-05-06', amount: '35,400.00', status: 'pending' },
  ];

  return (
    <div className="p-8 max-w-7xl mx-auto space-y-8">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-bold text-slate-900">Pawn Records</h1>
          <p className="text-slate-500 mt-1">Manage and audit all digitized pawn transactions.</p>
        </div>
        <div className="flex gap-3">
          <button className="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all flex items-center gap-2">
            <Download size={18} /> Export CSV
          </button>
          <button className="btn-primary">
            <CheckCircle size={18} /> Bulk Verify
          </button>
        </div>
      </div>

      {/* Filters Bar */}
      <div className="card p-4 flex flex-col md:flex-row gap-4 items-center">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by customer name or IR number..." 
            className="w-full bg-slate-50 border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-100 transition-all outline-none"
          />
        </div>
        <div className="flex gap-2 w-full md:w-auto">
          <select className="bg-white border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-100">
            <option>All Status</option>
            <option>Verified</option>
            <option>Pending</option>
            <option>Failed</option>
          </select>
          <button className="p-2.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-all">
            <Filter size={20} />
          </button>
        </div>
      </div>

      {/* Table Card */}
      <div className="card">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/80 border-b border-slate-100">
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Transaction ID</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Customer Details</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">IR Number</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Amount (Rs.)</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {records.map((record) => (
                <tr key={record.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-6 py-4">
                    <span className="text-xs font-bold text-slate-400">#REC-{record.id.toString().padStart(4, '0')}</span>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-9 h-9 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                        {record.customer.charAt(0)}
                      </div>
                      <span className="font-bold text-slate-900">{record.customer}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <span className="px-2 py-1 bg-slate-100 text-slate-600 rounded font-mono text-sm font-bold">
                      {record.ir}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-slate-500 text-sm font-medium">{record.date}</td>
                  <td className="px-6 py-4 text-right font-bold text-slate-900">{record.amount}</td>
                  <td className="px-6 py-4 text-center">
                    <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                      record.status === 'verified' ? 'bg-emerald-100 text-emerald-700' :
                      record.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                      'bg-rose-100 text-rose-700'
                    }`}>
                      {record.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-right">
                    <div className="flex justify-end gap-2">
                      <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        <Eye size={18} />
                      </button>
                      <button className="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                        <Trash2 size={18} />
                      </button>
                      <button className="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all">
                        <MoreVertical size={18} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        {/* Pagination */}
        <div className="p-6 border-t border-slate-100 flex justify-between items-center bg-slate-50/30">
          <p className="text-sm text-slate-500">Showing <span className="font-bold text-slate-900">1 to 5</span> of 128 records</p>
          <div className="flex gap-2">
            <button className="px-3 py-1 border border-slate-200 rounded-md text-sm font-medium hover:bg-white disabled:opacity-50" disabled>Previous</button>
            <button className="px-3 py-1 bg-blue-600 text-white rounded-md text-sm font-medium shadow-sm">1</button>
            <button className="px-3 py-1 border border-slate-200 rounded-md text-sm font-medium hover:bg-white">2</button>
            <button className="px-3 py-1 border border-slate-200 rounded-md text-sm font-medium hover:bg-white">Next</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Records;
