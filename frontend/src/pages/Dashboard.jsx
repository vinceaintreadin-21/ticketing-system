// Dashboard.jsx
import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import Sidebar from "../component/dashboard/Sidebar";
import DashboardLayout from "../component/dashboard/DashboardLayout";
import TicketForm from "../component/tickets/TicketForm"; // import modal

function handleLogout() {
  const token = localStorage.getItem("authToken");
  fetch("http://localhost:8000/api/logout", {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    },
  })
    .then((res) => {
      if (!res.ok) throw new Error("Logout failed");
      return res.json();
    })
    .then(() => {
      localStorage.removeItem("authToken");
      window.location.href = "/login";
    })
    .catch((err) => console.error(err));
}

export default function Dashboard() {
  const [user, setUser] = useState(null);
  const location = useLocation();
  const [successMessage, setSuccessMessage] = useState(null);
  const [showTicketForm, setShowTicketForm] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem("authToken");

    if (token) {
      fetch("http://localhost:8000/api/dashboard", {
        headers: { Authorization: `Bearer ${token}` },
      })
        .then((res) => {
          if (!res.ok) throw new Error("Unauthorized");
          return res.json();
        })
        .then((data) => setUser(data.user))
        .catch(() => localStorage.removeItem("authToken"));
    }

    if (location.state?.successMessage) {
      setSuccessMessage(location.state.successMessage);
      const timer = setTimeout(() => setSuccessMessage(null), 3000);
      return () => clearTimeout(timer);
    }
  }, [location.state]);

  if (!user) return <p>Loading...</p>;

  return (
    <DashboardLayout
      sidebar={
        <Sidebar
          user={user}
          handleLogout={handleLogout}
          onCreateTicket={() => setShowTicketForm(true)} // pass handler
        />
      }
    >
      {successMessage && (
        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
          {successMessage}
        </div>
      )}

      <h1 className="text-2xl font-bold">Welcome, {user.name} 👋</h1>
      <p className="mt-2 text-gray-700">This is your dashboard overview.</p>

      {/* Ticket modal */}
      <TicketForm
        show={showTicketForm}
        onClose={(success) => {
          setShowTicketForm(false);
          if (success) setSuccessMessage("Ticket submitted successfully!");
        }}
      />
    </DashboardLayout>
  );
}
