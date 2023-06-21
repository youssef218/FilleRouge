'use client';
import { useEffect, useState } from 'react';
import Link from "next/link";

const Reserve = () => {
  const [eventData, setEventData] = useState([]);

  


  const [isDeleted, setIsDeleted] = useState("");

  const handleDelete = async ({ id }) => {
    console.log(id);
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/delete/${id}`, {
        method: 'DELETE'
      });
      if (response.ok) {
        console.log(response);
              return response.json().then(data => {
                console.log(data.message);
                setIsDeleted(data.message);
                window.scrollTo(0, 0); // Scroll to the top of the page
                setTimeout(() => {
                    setIsDeleted("");
                  
                }, 10000);
              });
           
          
        
        // Handle any further actions or UI updates after successful deletion
      } else {
        console.error('Failed to delete event');
      }
    } catch (error) {
      console.error('Error deleting event:', error);
    }
  };

  
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/client/reservations', {
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
         {isDeleted && (
        <div className="alert alert-warning" role="alert">
          {isDeleted}
        </div>
      )}
    {eventData.length > 0 ? (
      <div className="row">
        {eventData.map(event => (
          <div className="col-md-4 mb-4" key={event.id}>
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">{event.event}</h5>
                {/* <p className="card-text">{event.description}</p> */}
                {/* <p className="card-text">Starts at: {event.debutAt}</p>
                <p className="card-text">Ends at: {event.finAt}</p> */}
                <p className="card-text">Created at: {event.createdAt}</p>
                <p className="card-text">prenstiel: {event.prenstiel ? 'Yes' : 'No'}</p>
                <button type="button" className="btn btn-outline-danger" onClick={() => handleDelete(event)}>Delete</button>
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

export default Reserve;
