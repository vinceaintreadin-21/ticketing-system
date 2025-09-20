import React from 'react';

export default function SSOLogin() {
    const backEndUrl = 'http://localhost:8000';

    const handleSSOLogin = (provider) => {
        window.location.href = `${backEndUrl}/api/auth/redirect/${provider}`;
    }

    return (
        <div>
            <h2>Login Page</h2>
            <button onClick={() => handleSSOLogin('google')}>Login with Google</button>
        </div>
    )


};
