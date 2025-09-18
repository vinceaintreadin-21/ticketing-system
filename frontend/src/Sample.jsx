import { useEffect, useState } from 'react';

export default function Sample() {
    const [message, setMessage] = useState('loading...');
    
    useEffect(() => {
        fetch('/api/sample-hello')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setMessage(data.message);
            })
            .catch(error => {
                console.error('Fetch error:', error);
                setMessage('Error fetching message');
            });
    }, []);

    return (
        <>
            <p>working...</p>
            <div>{message}</div>
        </>
    )
}
    
    