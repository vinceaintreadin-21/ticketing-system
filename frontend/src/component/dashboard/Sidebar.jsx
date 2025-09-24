import SidebarSection from "./SidebarSection";

export default function Sidebar({ user, handleLogout, onCreateTicket }) {
  return (
    <aside className="w-64 bg-white shadow-lg flex flex-col">
      <div className="p-4 flex items-center justify-between border-b">
        <h1 className="font-bold text-lg">iServe LVCC</h1>
      </div>

      <nav className="flex-1 p-4 space-y-4">
        <SidebarSection
          title="Main"
          items={[
            { label: "Dashboard", path: "/dashboard" },
            { label: "Status", path: "/status" },
            { label: "History", path: "/history" },
            { label: "Chats", path: "/chats" },
          ]}
        />
        <SidebarSection
          title="Manage"
          items={[
            { label: "My Tasks", path: "/tasks" },
            { label: "Users", path: "/users" },
            { label: "Settings", path: "/settings" },
            { label: "Create Ticket", onClick: onCreateTicket }, // use modal instead of route
          ]}
        />
      </nav>

      <div className="mt-auto p-4 border-t">
        <p className="text-sm text-gray-600">Logged in as:</p>
        <p className="font-medium">{user.name}</p>
        <p className="text-xs text-gray-500">{user.email}</p>
      </div>

      <button
        onClick={handleLogout}
        className="m-4 p-2 rounded-lg hover:bg-sky-100 cursor-pointer"
      >
        Logout
      </button>
    </aside>
  );
}
