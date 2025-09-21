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
            <div className='flex h-screen bg-gradient-to-b from-sky-300 to-sky-500'>
                <aside className='w-64 bg-white shadow-lg flex flex-col'>
                    <div className='p-4 flex items-center justify-between border-b'>
                        <h1 className='font-bold text-lg'>iServe LVCC</h1>
                    </div>
                    <nav className='flex-1 p-4 space-y-4'>
                        <div>
                            <h2 className='text-gray-500 text-xs uppercase'>Main</h2>
                            <ul className='space-y-2 mt-2'>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>Dashboard</li>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>Status</li>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>History</li>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>Chats</li>
                            </ul>
                        </div>
                        <div>
                            <h2 className='text-gray-500 text-xs uppercase'>Manage</h2>
                            <ul className='space-y-2 mt-2'>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>My Tasks</li>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>Users</li>
                                <li className='p-2 rounded-lg hover:bg-sky-100 cursor-pointer'>Settings</li>
                            </ul>
                        </div>
                        <div className='mt-auto p-4 border-t'>
                            <p className='text-sm text-gray-600'>Logged in as:</p>
                            <p className='font-medium'>{user.name}</p>
                            <p className='text-xs text-gray-500'>{user.email}</p>
                        </div>
                    </nav>
                    <div>
                        <button onClick={handleLogout} className=''>Logout</button>
                    </div>
                </aside>
            </div>

        </>

    );
}
