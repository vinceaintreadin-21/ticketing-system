import { Link } from "react-router-dom";

export default function SidebarSection({ title, items }) {
  return (
    <div>
      <h2 className="text-gray-500 text-xs uppercase">{title}</h2>
      <ul className="space-y-2 mt-2">
        {items.map(({ label, path, onClick }, idx) => (
          <li
            key={idx}
            className="p-2 rounded-lg hover:bg-sky-100 cursor-pointer"
            onClick={onClick}
          >
            {path ? <Link to={path}>{label}</Link> : label}
          </li>
        ))}
      </ul>
    </div>
  );
}
