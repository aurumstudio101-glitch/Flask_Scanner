import React from 'react';
import { Search, UserPlus, Mail, Phone, MapPin, MoreHorizontal, History } from 'lucide-react';

const Customers = () => {
  const customers = [
    { id: 1, name: 'Nisal Sayuranga', nic: '957602754V', phone: '0771192143', address: '6/7, Sri Wickrama Mawatha, Wattala', records: 12 },
    { id: 2, name: 'Suneth Perera', nic: '887234567V', phone: '0712345678', address: 'No 45, Main Road, Kiribathgoda', records: 5 },
    { id: 3, name: 'Wimal Siriwardena', nic: '752345678V', phone: '0759876543', address: '12, New Lane, Kadawatha', records: 8 },
    { id: 4, name: 'Aruna Shantha', nic: '902345678V', phone: '0781234567', address: '22/1, Cross Road, Kelaniya', records: 2 },
  ];

  return (
    <div className="p-8 max-w-7xl mx-auto space-y-8">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-slate-900">Customer Directory</h1>
          <p className="text-slate-500 mt-1">Manage client profiles and view their transaction history.</p>
        </div>
        <button className="btn-primary">
          <UserPlus size={20} /> Add New Customer
        </button>
      </div>

      <div className="flex gap-4">
        <div className="relative flex-1">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by name, NIC or phone..." 
            className="w-full bg-white border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-100 outline-none shadow-sm transition-all"
          />
        </div>
        <button className="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-all">Filters</button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {customers.map((customer) => (
          <div key={customer.id} className="card p-6 group hover:border-blue-300 hover:shadow-xl hover:shadow-blue-50 transition-all cursor-pointer">
            <div className="flex justify-between items-start mb-4">
              <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-xl border border-blue-100">
                {customer.name.charAt(0)}
              </div>
              <button className="p-2 text-slate-300 hover:text-slate-900 rounded-lg">
                <MoreHorizontal size={20} />
              </button>
            </div>
            
            <div className="space-y-1">
              <h3 className="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition-colors">{customer.name}</h3>
              <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">{customer.nic}</p>
            </div>

            <div className="mt-6 space-y-3">
              <div className="flex items-center gap-3 text-sm text-slate-600">
                <Phone size={16} className="text-slate-400" />
                <span>{customer.phone}</span>
              </div>
              <div className="flex items-start gap-3 text-sm text-slate-600">
                <MapPin size={16} className="text-slate-400 mt-1 shrink-0" />
                <span className="line-clamp-2">{customer.address}</span>
              </div>
            </div>

            <div className="mt-6 pt-6 border-t border-slate-50 flex justify-between items-center">
              <div className="flex items-center gap-2">
                <div className="px-2 py-1 bg-blue-50 text-blue-600 rounded text-[10px] font-black uppercase">
                  {customer.records} Records
                </div>
              </div>
              <button className="flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline">
                <History size={14} /> View History
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Customers;
