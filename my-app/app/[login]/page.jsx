'use client';
import { useState, useEffect } from "react";
import Link from "next/link";
import { useRouter } from 'next/navigation';
const login = ({ params: { login } }) => {

    const router = useRouter();

    const [formData, setFormData] = useState({
        email: '',
        tele: '',
        ville: '',
        cin: '',
        fullName: '',
        password: ''
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prevFormData => ({
            ...prevFormData,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await fetch("http://127.0.0.1:8000/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            if (response.ok) {
                console.log("Registration successful");
                router.push("/login");
                // Handle successful registration, such as redirecting to a success page or showing a success message
            } else {
                // Handle registration error
                const errorData = await response.json();
                console.log("Registration error:", errorData);
            }
        } catch (error) {
            // Handle any network or fetch errors
            console.log("An error occurred. Please try again.", error);
        }
    };









    const [profileData, setProfileData] = useState(null);
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [errorMessage, setErrorMessage] = useState("");
    const handleFormSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch("http://127.0.0.1:8000/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    username: email,
                    password: password,
                }),
                credentials: "include", // Include cookies in subsequent requests
            });
            console.log(response.headers);
            if (response.ok) {
                const data = await response.json();
                if (data.user) {
                    console.log(data);
                    window.location.href = "/";
                }
            } else if (response.status === 401) {
                // Handle invalid credentials
                const errorData = await response.json();
                setErrorMessage(errorData.error);
            }
        } catch (error) {
            // Handle any network or fetch errors
            setErrorMessage("An error occurred. Please try again.");
        }
    };








    const [showForm, setShowForm] = useState(true);
    useEffect(() => {
        if (login === "login") {
            setShowForm(false);
        } else {
            setShowForm(true);
        }
    }, [login]);
    return (
        <div>
            <section className="vh-100 bg-image">
                <div className="mask d-flex align-items-center h-100" >
                    <div className="container h-100">
                        <div className="row d-flex justify-content-center align-items-center h-100 mt-5">
                            <div className="col-12 col-md-9 col-lg-7 col-xl-6 ">
                                <div className="card">
                                    <div className="card-body p-5">
                                        {showForm ? (
                                            <>
                                                <h2 className="text-uppercase text-center mb-5">
                                                    Create an account
                                                </h2>
                                                <form onSubmit={handleSubmit}>
                                                    <div className="form-outline mb-4 ">
                                                        <input
                                                            type="text"

                                                            className="form-control form-control-lg"
                                                            name="fullName"
                                                            value={formData.fullName}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example1cg">
                                                            Fulle Name
                                                        </label>
                                                    </div>


                                                    <div className="form-outline mb-4">
                                                        <input
                                                            autoComplete="username"
                                                            type="email"
                                                            className="form-control form-control-lg"
                                                            name="email"
                                                            value={formData.email}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example3cg">
                                                            Your Email
                                                        </label>
                                                    </div>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            autoComplete="current-password"
                                                            type="password"
                                                            className="form-control form-control-lg"
                                                            name="password"
                                                            value={formData.password}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example4cg">
                                                            Password
                                                        </label>
                                                    </div>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            type="text"
                                                            className="form-control form-control-lg"
                                                            name="tele"
                                                            value={formData.tele}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example1cg">
                                                            telephone
                                                        </label>
                                                    </div>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            type="text"
                                                            className="form-control form-control-lg"
                                                            name="cin"
                                                            value={formData.cin}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example1cg">
                                                            CIN
                                                        </label>
                                                    </div>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            type="text"
                                                            className="form-control form-control-lg"
                                                            name="ville"
                                                            value={formData.ville}
                                                            onChange={handleChange}

                                                        />
                                                        <label className="form-label" htmlFor="form3Example1cg">
                                                            ville
                                                        </label>
                                                    </div>

                                                    <div className="form-check d-flex justify-content-center mb-5">
                                                        <input
                                                            className="form-check-input me-2"
                                                            type="checkbox"
                                                            value=""
                                                            id="form2Example3cg"

                                                        />
                                                        <label className="form-check-label" htmlFor="form2Example3g">
                                                            I agree all statements in{" "}
                                                            <a href="#!" className="text-body">
                                                                <u>Terms of service</u>
                                                            </a>
                                                        </label>
                                                    </div>

                                                    <div className="d-flex justify-content-center">
                                                        <button
                                                            type="submit"
                                                            className="btn btn-info btn-block btn-lg text-body"
                                                        >
                                                            Register
                                                        </button>
                                                    </div>
                                                    <p className="text-center text-muted mt-5 mb-0">
                                                        Have already an account?{" "}
                                                        <Link href="/login" className="fw-bold text-body">
                                                            Sign In
                                                        </Link>
                                                    </p>
                                                </form>
                                            </>
                                        ) : (
                                            <>
                                                <h2 className="text-uppercase text-center mb-5">
                                                    Sign in
                                                </h2>
                                                {errorMessage && <p className="error-message">{errorMessage}</p>}
                                                <form onSubmit={handleFormSubmit}>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            autoComplete="username"
                                                            type="email"
                                                            id="form3Example3cg"
                                                            className="form-control form-control-lg"
                                                            onChange={(e) => setEmail(e.target.value)}
                                                        />
                                                        <label className="form-label" htmlFor="form3Example3cg">
                                                            Your Email
                                                        </label>
                                                    </div>

                                                    <div className="form-outline mb-4">
                                                        <input
                                                            autoComplete="current-password"
                                                            type="password"
                                                            id="form3Example4cg"
                                                            className="form-control form-control-lg"
                                                            onChange={(e) => setPassword(e.target.value)}
                                                        />
                                                        <label className="form-label" htmlFor="form3Example4cg">
                                                            Password
                                                        </label>
                                                    </div>


                                                    <div className="d-flex justify-content-center">
                                                        <button
                                                            type="submit"
                                                            className="btn btn-info btn-block btn-lg text-body"
                                                        >
                                                            sign in
                                                        </button>
                                                    </div>

                                                    <p className="text-center text-muted mt-5 mb-0">
                                                        Don't have an account?{" "}
                                                        {/*   <a href="#!" className="fw-bold text-body">
                                                            <u>register here</u>
                                                        </a> */}
                                                        <Link href="/register" className="fw-bold text-body">
                                                            Sign Up
                                                        </Link>
                                                    </p>
                                                </form>
                                            </>
                                        )}


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    );
};

export default login;
