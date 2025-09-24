import { useState } from "react";

export default function FileUpload({ onFileSelect }) {
  const [fileName, setFileName] = useState(null);
  const [previewUrl, setPreviewUrl] = useState(null);
  const [dragOver, setDragOver] = useState(false);

  const handleFile = (file) => {
    if (file) {
      setFileName(file.name);
      onFileSelect(file);

      // ✅ Create preview if it's an image
      if (file.type.startsWith("image/")) {
        const url = URL.createObjectURL(file);
        setPreviewUrl(url);
      } else {
        setPreviewUrl(null);
      }
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    setDragOver(false);
    const file = e.dataTransfer.files[0];
    handleFile(file);
  };

  return (
    <div
      className={`border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition flex items-center justify-center
        ${dragOver ? "border-blue-400 bg-blue-50" : "border-gray-300"}`}
      onDragOver={(e) => {
        e.preventDefault();
        setDragOver(true);
      }}
      onDragLeave={() => setDragOver(false)}
      onDrop={handleDrop}
      onClick={() => document.getElementById("fileInput").click()}
      style={{ minHeight: "200px" }} // you can adjust height
    >
      <input
        id="fileInput"
        type="file"
        className="hidden"
        onChange={(e) => handleFile(e.target.files[0])}
        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
      />

      <div className="flex flex-col items-center w-full h-full">
        {previewUrl ? (
          <img
            src={previewUrl}
            alt="Preview"
            className="w-full h-full object-contain rounded"
          />
        ) : fileName ? (
          <p className="text-sm text-gray-700">{fileName}</p>
        ) : (
          <>
            <svg
              className="w-10 h-10 text-gray-400 mb-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 010 10h-1"
              />
            </svg>
            <p className="text-sm text-gray-600">
              Click to upload or drag and drop
            </p>
            <p className="text-xs text-gray-500">
              PDF, DOC, JPG, PNG (Max 10MB)
            </p>
          </>
        )}
      </div>
    </div>
  );
}
