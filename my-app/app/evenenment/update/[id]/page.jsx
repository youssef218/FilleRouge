'use client';
import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';

const EventForm = ({ params: { id } }) => {
    const router = useRouter();

    const [eventData, setEventData] = useState({
        id: '',
        titre: '',
        description: '',
        debutAt: '',
        finAt: '',
        creatAt: '',
        isPublic: false
    });

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/events/${id}`);

                if (response.status === 404) {
                    setEventData(null);
                } else {
                    const data = await response.json();
                    const formattedDebutAt = data.debutAt ? new Date(data.debutAt).toISOString().slice(0, 16) : '';
                    const formattedFinAt = data.finAt ? new Date(data.finAt).toISOString().slice(0, 16) : '';
                    const formattedcreAt = data.creatAt ? new Date(data.finAt).toISOString().slice(0, 16) : '';

                    setEventData({
                        id: data.id,
                        titre: data.titre,
                        description: data.description,
                        debutAt: formattedDebutAt,
                        finAt: formattedFinAt,
                        creatAt: formattedcreAt,
                        isPublic: data.isPublic
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        fetchData();
    }, [id]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        // Format the date fields before submitting
        const formattedEventData = { ...eventData };

        if (formattedEventData.debutAt) {
            const debutAtDate = new Date(formattedEventData.debutAt);
            const formattedDebutAt = `${debutAtDate.getFullYear()}-${(debutAtDate.getMonth() + 1).toString().padStart(2, '0')}-${debutAtDate.getDate().toString().padStart(2, '0')} ${debutAtDate.getHours().toString().padStart(2, '0')}:${debutAtDate.getMinutes().toString().padStart(2, '0')}:${debutAtDate.getSeconds().toString().padStart(2, '0')}`;
            formattedEventData.debutAt = formattedDebutAt;
        }

        if (formattedEventData.finAt) {
            const finAtDate = new Date(formattedEventData.finAt);
            const formattedFinAt = `${finAtDate.getFullYear()}-${(finAtDate.getMonth() + 1).toString().padStart(2, '0')}-${finAtDate.getDate().toString().padStart(2, '0')} ${finAtDate.getHours().toString().padStart(2, '0')}:${finAtDate.getMinutes().toString().padStart(2, '0')}:${finAtDate.getSeconds().toString().padStart(2, '0')}`;
            formattedEventData.finAt = formattedFinAt;
        }
        if (formattedEventData.creatAt) {
            const creatAtDate = new Date(formattedEventData.creatAt);
            const formattedcreatAt = `${creatAtDate.getFullYear()}-${(creatAtDate.getMonth() + 1).toString().padStart(2, '0')}-${creatAtDate.getDate().toString().padStart(2, '0')} ${creatAtDate.getHours().toString().padStart(2, '0')}:${creatAtDate.getMinutes().toString().padStart(2, '0')}:${creatAtDate.getSeconds().toString().padStart(2, '0')}`;
            formattedEventData.creatAt = formattedcreatAt;
        }

        try {
            console.log(formattedEventData);
            const response = await fetch(`http://127.0.0.1:8000/api/update/evenement/${id}`, {
                method: 'PUT',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            });
            if (response.ok) {
                console.log('Event successfully updated');
                router.push('/evenenment');
            } else {
                console.error('Failed to update event');
            }
        } catch (error) {
            console.error('Error updating event:', error);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        let formattedDate = '';
        if (name === 'debutAt' || name === 'finAt') {
            const date = new Date(value);
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const seconds = date.getSeconds().toString().padStart(2, '0');
            formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
        setEventData({ ...eventData, [name]: formattedDate || value });
    };

    return (
        <>
            {eventData ? (
                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>ID:</label>
                        <input type="text" className="form-control" name="id" value={eventData.id} readOnly />
                    </div>
                    <div className="form-group">
                        <label>Titre:</label>
                        <input type="text" className="form-control" name="titre" value={eventData.titre} onChange={handleChange} />
                    </div>
                    <div className="form-group">
                        <label>Description:</label>
                        <input type="text" className="form-control" name="description" value={eventData.description} onChange={handleChange} />
                    </div>
                    <div className="form-group mb-4">
                        <label className=' mb-4'>Debut At:</label>
                        <input type="datetime-local" className="form-control" name="debutAt" value={eventData.debutAt} onChange={handleChange} required />
                    </div>
                    <div className="form-group mb-4">
                        <label className=' mb-4'>Fin At:</label>
                        <input type="datetime-local" className="form-control" name="finAt" value={eventData.finAt} onChange={handleChange} required />
                    </div>
                    <div className="form-group">
                        <label>Created At:</label>
                        <p>{eventData.creatAt}</p>
                    </div>
                    <div className="form-group">
                        <label>Is Public:</label>
                        <select className="form-control" name="isPublic" value={eventData.isPublic} onChange={handleChange}>
                            <option value={true}>Yes</option>
                            <option value={false}>No</option>
                        </select>
                    </div>
                    <button type="submit" className="btn btn-primary">Update Event</button>
                </form>
            ) : (
                <h2>Event not found</h2>
            )}
        </>
    );
};

export default EventForm;
