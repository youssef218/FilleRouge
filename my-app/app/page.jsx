'use client';
import { useEffect, useState } from 'react';

const Home = () => {
  const [eventData, setEventData] = useState([]);
  const [eventBadReq, setEventBadReq] = useState("");
  const [eventSuccess, setEventSuccess] = useState("");

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/events', {
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
  }, []);

  function handleButtonClick(id) {
    const url = `http://127.0.0.1:8000/api/reservation/${id}`;
    fetch(url, {
      method: 'POST',
      credentials: 'include',
      redirect: 'follow',
    })
      .then(response => {
        if (response.status === 400 || response.status === 409) {
          return response.json().then(data => {
            console.log(data.message);
            setEventBadReq(data.message);
            window.scrollTo(0, 0); // Scroll to the top of the page
            setTimeout(() => {
              setEventBadReq("");
            }, 10000);
          });
        } else if (response.status === 200) {
          return response.json().then(data => {
            console.log(data.message);
            setEventSuccess(data.message);
            window.scrollTo(0, 0); // Scroll to the top of the page
            setTimeout(() => {
              setEventSuccess("");
            }, 10000);
          });
        } else {
          return response.json();
        }
      })
      .catch(error => {
        // Handle any errors
        console.error(error);
      });
  }

  return (
    <div className="container mt-5">
      {eventBadReq && (
        <div className="alert alert-warning" role="alert">
          {eventBadReq}
        </div>
      )}

      {eventSuccess && (
        <div className="alert alert-success" role="alert">
          {eventSuccess}
        </div>
      )}

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
                  <button
                    onClick={() => handleButtonClick(event.id)}
                    className="btn btn-primary"
                  >
                    Reserve
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <p>No events available now.</p>
      )}
    </div>
  );
};

export default Home;
