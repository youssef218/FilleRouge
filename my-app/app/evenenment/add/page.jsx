'use client';
import { useState } from 'react';
import { useRouter } from 'next/navigation';

function AddEventForm() {
  const router = useRouter();

  const [eventData, setEventData] = useState({
    titre: '',
    description: '',
    isPublic: false,
    debutAt: '',
    finAt: ''
  });

  const handleChange = (event) => {
    const { name, value } = event.target;
    let formattedDate = '';
    if (name === 'debutAt' || name === 'finAt') {
      const date = new Date(value);
      formattedDate = date.toISOString().slice(0, 19).replace('T', ' ');
    }
    setEventData({ ...eventData, [name]: formattedDate || value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await fetch('http://127.0.0.1:8000/api/add/evenement', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(eventData)
      });
      const data = await response.json();
      console.log('Event added:', data);
      // reset form data
      setEventData({
        titre: '',
        discription: '',
        isPublic: false,
        debutAt: '',
        finAt: ''
      });
      router.push('/evenenment');
    } catch (error) {
      console.error('Error adding event:', error);
    }
  };

  return (


<>

<div className="card mt-5" style={{width: '70rem'}}>
  <div className="card-body ">

   
  <form onSubmit={handleSubmit} className=' container'>
      <div className="form-group mb-4 ">
        <label className=' mb-4'>Titre:</label>
        <input type="text" className="form-control" name="titre" value={eventData.titre} onChange={handleChange} required />
      </div>
      <div className="form-group mb-4">
        <label className=' mb-4'>Description:</label>
        <textarea className="form-control" name="discription" value={eventData.discription} onChange={handleChange} required></textarea>
      </div>
      <div className="form-group mb-4">
        <label className=' mb-4'>Is Public:</label>
        <select className="form-control" name="isPublic" value={eventData.isPublic} onChange={handleChange} required>
          <option value={false}>No</option>
          <option value={true}>Yes</option>
        </select>
      </div>
      <div className="form-group mb-4">
        <label className=' mb-4'>Debut At:</label>
        <input type="datetime-local" className="form-control" name="debutAt" value={eventData.debutAt} onChange={handleChange} required />
      </div>
      <div className="form-group mb-4">
        <label className=' mb-4'>Fin At:</label>
        <input type="datetime-local" className="form-control" name="finAt" value={eventData.finAt} onChange={handleChange} required />
      </div>
      <button type="submit" className="btn btn-primary">Add Event</button>
    </form>
  </div>
</div>







</>




   
  );
}
export default AddEventForm;