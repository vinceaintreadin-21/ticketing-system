export default function DashboardLayout({ sidebar, children }) {
  return (
    <div className="flex h-screen bg-gradient-to-b from-sky-300 to-sky-500">
      {sidebar}
      <main className="flex-1 p-6 overflow-y-auto">{children}</main>
    </div>
  );
}
