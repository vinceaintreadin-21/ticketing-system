import { useState, useEffect } from "react";
import FileUpload from "./FileUpload";

export default function TicketForm({ show, onClose }) {
  const [categories, setCategories] = useState([]);
  const [categoryId, setCategoryId] = useState("");
  const [urgency, setUrgency] = useState("low");
  const [description, setDescription] = useState("");
  const [file, setFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState(null);
  const [fileWarning, setFileWarning] = useState(null);

  const token = localStorage.getItem("authToken");

  // Fetch categories on mount
  useEffect(() => {
    if (!token || !show) return; // only fetch when modal is open

    const fetchCategories = async () => {
      try {
        const res = await fetch("http://localhost:8000/api/categories", {
          headers: { Authorization: `Bearer ${token}` },
        });
        if (!res.ok) throw new Error("Failed to load categories");
        const data = await res.json();
        setCategories(data);
      } catch (err) {
        console.error(err);
        setErrorMessage("Could not load categories.");
      }
    };

    fetchCategories();
  }, [token, show]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrorMessage(null);
    setFileWarning(null);

    if (!categoryId || !description.trim()) {
      setErrorMessage("Please fill in all required fields.");
      setLoading(false);
      return;
    }

    try {
      // 1️⃣ Create ticket
      const ticketRes = await fetch("http://localhost:8000/api/tickets", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          category_id: categoryId,
          urgency_level: urgency,
          issue_description: description,
        }),
      });

      if (!ticketRes.ok) {
        const errorData = await ticketRes.json();
        throw new Error(errorData.message || "Ticket creation failed");
      }

      const ticketData = await ticketRes.json();
      const ticketId = ticketData.id;

      // 2️⃣ Upload file if provided
      if (file) {
        const formData = new FormData();
        formData.append("note_type", "external");
        formData.append("content", description);
        formData.append("file", file);

        try {
          const fileRes = await fetch(
            `http://localhost:8000/api/ticket-notes/${ticketId}`,
            {
              method: "POST",
              headers: { Authorization: `Bearer ${token}` },
              body: formData,
            }
          );

          if (!fileRes.ok) {
            let warningMessage = "File upload warning: ";
            try {
              const data = await fileRes.json();
              warningMessage += data.message || JSON.stringify(data);
            } catch {
              const text = await fileRes.text();
              warningMessage += text;
            }
            console.warn(warningMessage);
            setFileWarning(warningMessage);
          }
        } catch (err) {
          console.warn("File upload exception:", err.message);
          setFileWarning("File upload failed: " + err.message);
        }
      }

      // ✅ Close modal and show success
      onClose(true);
    } catch (err) {
      console.error(err);
      setErrorMessage(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (!show) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
        <button
          className="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
          onClick={() => onClose(false)}
        >
          ✕
        </button>

        <h2 className="font-bold text-lg mb-4">Submit a Ticket</h2>

        {errorMessage && (
          <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-2">
            {errorMessage}
          </div>
        )}

        {fileWarning && (
          <div className="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-2 rounded mb-2">
            {fileWarning}
          </div>
        )}

        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <select
            value={categoryId}
            onChange={(e) => setCategoryId(e.target.value)}
            className="border p-2"
            required
          >
            <option value="">-- Select Category --</option>
            {categories.map((cat) => (
              <option key={cat.id} value={cat.id}>
                {cat.category_name}
              </option>
            ))}
          </select>

          <select
            value={urgency}
            onChange={(e) => setUrgency(e.target.value)}
            className="border p-2"
          >
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>

          <textarea
            placeholder="Issue Description"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            className="border p-2"
            required
          />

          <FileUpload
            onFileSelect={setFile}
          />

          <button
            type="submit"
            disabled={loading}
            className="bg-blue-500 text-white px-4 py-2 disabled:bg-gray-400 rounded"
          >
            {loading ? "Submitting..." : "Submit Ticket"}
          </button>
        </form>
      </div>
    </div>
  );
}
