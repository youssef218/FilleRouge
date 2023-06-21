"use client";
import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import 'bootstrap-icons/font/bootstrap-icons.css'

const Profile = () => {
  const router = useRouter();
  const [result, setResult] = useState(null);

  useEffect(() => {
    const fetchProfileData = async () => {
      const requestOptions = {
        method: 'GET',
        credentials: 'include',
        redirect: 'follow',
      };

      try {
        const response = await fetch('http://127.0.0.1:8000/api/profile', requestOptions);
        if (response.status === 200) {
          const result = await response.json();
          setResult(result);
        } else {
          console.log('error', response.status);
        }
      } catch (error) {
        if (error.response && error.response.status === 401) {
          router.push('/signin');
        } else {
          console.log('error', error);
        }
      }
    };

    fetchProfileData();
  }, [router]);

  return <div>
    {result ? (
      <>
      <div className="container py-5 h-100">
        <div className="row d-flex justify-content-center align-items-center h-100">
          <div className="col col-lg-12 mb-4 mb-lg-0">
            <div className="card mb-3" style={{ borderRadius: '0.5rem' }}>
              <div className="row g-0">
                <div
                  className="col-md-4 text-center text-white"
                  style={{
                    borderRadius: '0.5rem',
                    background: '#f6d365',
                    background:
                      '-webkit-linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1))',
                    background:
                      'linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1))',
                  }}
                >
                  {/* <img
                    src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                    alt="Avatar"
                    className="img-fluid my-5"
                    style={{ width: '80px' }}
                  /> */}
                  <div className="rounded-circle fs-3 my-5">
                    <span>
                      <i className="bi bi-person-circle"></i>
                    </span>
                  </div>
                  <h5>{result.fullName}</h5>
                  <p>{result.roles.join(', ')}</p>
                  <i className="bi bi-pencil-square"></i>
                </div>
                <div className="col-md-8">
                  <div className="card-body p-4">
                    <h6>Information</h6>
                    <hr className="mt-0 mb-4" />
                    <div className="row pt-1">
                      <div className="col-6 mb-3">
                        <h6>Email</h6>
                        <p className="text-muted">{result.email}</p>
                      </div>
                      <div className="col-6 mb-3">
                        <h6>Phone</h6>
                        <p className="text-muted">{result.teleportable}</p>
                      </div>
                    </div>
                    <div className="row pt-1">
                      <div className="col-6 mb-3">
                        <h6>Address</h6>
                        <p className="text-muted">{result.adress}</p>
                      </div>
                      <div className="col-6 mb-3">
                        <h6>CIN</h6>
                        <p className="text-muted">{result.cin}</p>
                      </div>
                    </div>


                    <h6>Plus options</h6>
                    <hr className="mt-0 mb-4" />
                    <div className="row pt-1">
                      <div className="col-6 mb-3">
                        <p className="text-muted">No option</p>
                      </div>
                    </div>
                    <div className="d-flex justify-content-around col-3">
                      <a href="#!" >
                      <i className="bi bi-facebook"></i>
                      </a>
                      <a href="#!">
                      <i className="bi bi-twitter"></i>
                      </a>
                      <a href="#!">
                      <i className="bi bi-instagram"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </>
      ) : (
        <p>Loading...</p>
      )}
  </div>;
};

export default Profile;