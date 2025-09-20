import React, {useEffect} from 'react';
import {useNavigate, useSearchParams} from 'react-router-dom';

export default function SSOCallback() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    useEffect(() => {
        const token = searchParams.get('token');

        if (token) {
            localStorage.setItem('authToken', token);
            
            navigate('/dashboard', {replace: true});
        }

    }, [searchParams, navigate]);

    return (
        <p>Processing Login</p>
    )
}
