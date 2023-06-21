'use client';
import { useEffect, useState } from 'react';
import Link from "next/link";

const EventCards = () => {
  const [eventData, setEventData] = useState([]);

  


  const [isDeleted, setIsDeleted] = useState(false);
   const [isNDeleted, setIsNDeleted] = useState(false);

  const handleDelete = async ({ id }) => {
    console.log(id);
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/remove/evenement/${id}`, {
        method: 'DELETE'
      });
      if (response.ok) {
        setIsDeleted(true);
        setIsNDeleted(false);
        // Handle any further actions or UI updates after successful deletion
      } else {
        setIsNDeleted(true);
        setIsDeleted(false);
        console.error('Failed to delete event');
      }
    } catch (error) {
        setIsNDeleted(true);
        setIsDeleted(false);
      console.error('Error deleting event:', error);
    }
  };

  
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/client/events', {
          method: 'GET',
          credentials: 'include',
          redirect: 'follow'
        });
        if (response.ok) {
          const data = await response.json();
          setEventData(data);
        } else {
          setEventData(null);
        }
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };
  
    fetchData();
  }, [isDeleted]);

  return (
    <div className="container mt-5">
    {isDeleted ? <div className="alert alert-success" role="alert">Event successfully deleted!!</div> : ''}
    {isNDeleted ? <div className="alert alert-danger" role="alert">Event doesn't delete because it has reservations!!</div> : ''}
    {eventData.length > 0 ? (
      <div className="row">
        {eventData.map(event => (
          <div className="col-md-4 mb-4" key={event.id}>
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">{event.titre}</h5>
                <p className="card-text">{event.description}</p>
                <p className="card-text">Starts at: {event.debutAt}</p>
                <p className="card-text">Ends at: {event.finAt}</p>
                <p className="card-text">Created at: {event.creatAt}</p>
                <p className="card-text">Public: {event.isPublic ? 'Yes' : 'No'}</p>
                <button type="button" className="btn btn-outline-danger" onClick={() => handleDelete(event)}>Delete</button>
                <Link href={`/evenenment/update/${event.id}`} className="ms-3 btn btn-outline-info">Update</Link>
              </div>
            </div>
          </div>
        ))}
      </div>
    ) : (
      <p>No events available.</p>
    )}
  </div>
  );
};

export default EventCards;
