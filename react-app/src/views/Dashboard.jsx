import React from 'react';
import { 
  TrendingUp, 
  Users, 
  FileText, 
  Clock,
  ArrowUpRight,
  ArrowDownRight
} from 'lucide-react';

const StatCard = ({ title, value, icon: Icon, trend, trendValue, color }) => (
  <div className="card p-6 flex flex-col gap-4">
    <div className="flex justify-between items-start">
      <div className={`p-3 rounded-xl bg-${color}-50 text-${color}-600`}>
        <Icon size={24} />
      </div>
      <div className={`flex items-center gap-1 text-sm ${trend === 'up' ? 'text-emerald-600' : 'text-rose-600'} font-bold bg-slate-50 px-2 py-1 rounded-lg`}>
        {trend === 'up' ? <ArrowUpRight size={16} /> : <ArrowDownRight size={16} />}
        {trendValue}
      </div>
    </div>
    <div>
      <p className="text-slate-500 text-sm font-medium">{title}</p>
      <h2 className="text-3xl font-bold mt-1">{value}</h2>
    </div>
  </div>
);

const Dashboard = () => {
  return (
    <div className="p-8 max-w-7xl mx-auto space-y-8">
      <div className="flex justify-between items-end">
        <div>
          <h1 className="text-3xl font-bold text-slate-900">Executive Overview</h1>
          <p className="text-slate-500 mt-1">Welcome back, here's what's happening today.</p>
        </div>
        <div className="flex gap-3">
          <button className="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all">Export Report</button>
          <button className="btn-primary">New Scan</button>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard 
          title="Daily Revenue" 
          value="Rs. 125,450" 
          icon={TrendingUp} 
          trend="up" 
          trendValue="+12.5%" 
          color="blue"
        />
        <StatCard 
          title="New Customers" 
          value="48" 
          icon={Users} 
          trend="up" 
          trendValue="+5.2%" 
          color="indigo"
        />
        <StatCard 
          title="Total Bills" 
          value="1,284" 
          icon={FileText} 
          trend="down" 
          trendValue="-2.4%" 
          color="purple"
        />
        <StatCard 
          title="Pending Verifications" 
          value="12" 
          icon={Clock} 
          trend="up" 
          trendValue="+3" 
          color="amber"
        />
      </div>

      {/* Main Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Recent Records Table */}
        <div className="lg:col-span-2 card">
          <div className="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 className="font-bold text-lg">Recent Transactions</h3>
            <button className="text-blue-600 text-sm font-bold hover:underline">View All</button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-slate-50/50">
                  <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Customer</th>
                  <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase">IR No</th>
                  <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Amount</th>
                  <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {[
                  { name: 'Nisal Sayuranga', ir: 'IR-9482', amount: 'Rs. 45,000', status: 'verified' },
                  { name: 'Chaminda Perera', ir: 'IR-9483', amount: 'Rs. 12,000', status: 'pending' },
                  { name: 'Ruwan Kumara', ir: 'IR-9484', amount: 'Rs. 85,000', status: 'verified' },
                  { name: 'Kamal Silva', ir: 'IR-9485', amount: 'Rs. 32,500', status: 'failed' },
                ].map((row, i) => (
                  <tr key={i} className="hover:bg-slate-50 transition-colors group">
                    <td className="px-6 py-4 font-medium text-slate-900">{row.name}</td>
                    <td className="px-6 py-4 text-slate-500 font-mono text-sm">{row.ir}</td>
                    <td className="px-6 py-4 font-bold text-slate-900">{row.amount}</td>
                    <td className="px-6 py-4">
                      <span className={`px-2 py-1 rounded-full text-[10px] font-bold uppercase ${
                        row.status === 'verified' ? 'bg-emerald-100 text-emerald-700' :
                        row.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                        'bg-rose-100 text-rose-700'
                      }`}>
                        {row.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Branch Performance */}
        <div className="card p-6">
          <h3 className="font-bold text-lg mb-6">Branch Performance</h3>
          <div className="space-y-6">
            {[
              { branch: 'Wattala', value: 85, color: 'bg-blue-600' },
              { branch: 'Kiribathgoda', value: 65, color: 'bg-indigo-600' },
              { branch: 'Kadawatha', value: 45, color: 'bg-purple-600' },
              { branch: 'Office', value: 92, color: 'bg-emerald-600' },
            ].map((item, i) => (
              <div key={i} className="space-y-2">
                <div className="flex justify-between text-sm font-medium">
                  <span>{item.branch}</span>
                  <span className="text-slate-500">{item.value}%</span>
                </div>
                <div className="h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div 
                    className={`h-full ${item.color} rounded-full transition-all duration-1000`} 
                    style={{ width: `${item.value}%` }}
                  />
                </div>
              </div>
            ))}
          </div>
          <button className="w-full mt-8 py-3 border-2 border-slate-100 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-all">Detailed Analytics</button>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
