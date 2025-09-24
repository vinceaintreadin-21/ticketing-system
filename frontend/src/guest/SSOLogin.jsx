import React from 'react';

export default function SSOLogin() {
    const backEndUrl = 'http://localhost:8000';

    const handleSSOLogin = (provider) => {
        window.location.href = `${backEndUrl}/api/auth/redirect/${provider}`;
    }

    return (
        // <div>
        //     <h2>Login Page</h2>
        //     <button onClick={() => handleSSOLogin('google')}>Login with Google</button>
        // </div>
        <div className='flex h-screen'>
            <div className='w-1/2 flex items-center justify-center bg-white shadow-lg'>
                <div className='w-2/3 max-w-md'>
                    <h1 className='text-3x1 font-bold mb-2'>Welcome</h1>
                    <h2 className='text-lg font-semibold mb-6'>iServe Ticketing System</h2>
                    <button onClick={() => handleSSOLogin("google")} className="w-full flex items-center justify-center gap-2 bg-sky-500 text-white rounded-lg py-2 shadow hover:bg-sky-600 transition">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" className="w-5 h-5"/>
                        Continue with Google
                    </button>
                </div>

            </div>
            <div className='w-1/2 bg-gradient-to-b from-sky-300 to-sky-600'></div>
        </div>
    )
};
