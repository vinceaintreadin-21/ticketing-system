import { useEffect, useState } from 'react';

function handleLogout() {
    const token = localStorage.getItem("authToken");

    fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": 'application/json'
        }
    })
    .then((res) => {
        if (!res.ok) throw new Error("Logout failed");
        return res.json();

    })
    .then(() => {
        localStorage.removeItem("authToken");
        window.location.href = '/login';
    })
    .catch((err) => console.error(err));
}

export default function Dashboard() {
    const [user, setUser] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem("authToken");

        if (token) {
            fetch("http://localhost:8000/api/dashboard", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
                .then((res) => {
                    if (!res.ok) throw new Error("Unauthorized");
                    return res.json();
                })
                .then((data) => setUser(data.user))
                .catch(() => {
                    localStorage.removeItem("authToken");
                });
        }
    }, []);

    if (!user) return <p>Loading...</p>;

    return (
        <>
            <div>
                <h1>Dashboard</h1>
                <p>Welcome, {user.name}</p>
                <p>Email: {user.email}</p>
                {user.avatar && <img src={user.avatar} alt="avatar" width="80" />}
            </div>

            <button onClick={handleLogout}>Logout</button>
        </>

    );
}
