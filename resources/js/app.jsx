import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";

import HeaderNavbar from "./components/HeaderNavbar/HeaderNavbar";
import Footer from "./components/Footer/Footer";

import Content1 from "./components/Content1/Content1";
import Content3 from "./components/Content3/Content3";
import Content4 from "./components/Content4/Content4";
import Content5 from "./components/Content5/Content5";
import Content6 from "./components/Content6/Content6";
import Content7 from "./components/Content7/Content7";
import Content8 from "./components/Content8/Content8";
import Content9 from "./components/Content9/Content9";
import Content10 from "./components/Content10/Content10";
import Content11 from "./components/Content11/Content11";

import SignUser from "./pages/SignUser/SignUser";
import SignUp from "./pages/Signup/SignUp";
import Login from "./pages/Login/Login";
import Dashboard from "./pages/Dashboard/Dashboard";

const HomePage = () => (
    <>
        <HeaderNavbar />
        <Content1 />
        <Content3 />
        <Content4 />
        <Content5 />
        <Content6 />
        <Content7 />
        <Content8 />
        <Content9 />
        <Content10 />
        <Content11 />
        <Footer />
    </>
);

const Layout = ({ children }) => (
    <>
        <HeaderNavbar />
        {children}
        <Footer />
    </>
);

const App = () => (
    <BrowserRouter basename="/coachsparkle">
        <Routes>
            <Route path="/" element={<HomePage />} />
            <Route
                path="/signuser"
                element={
                    <Layout>
                        <SignUser />
                    </Layout>
                }
            />
            <Route
                path="/signup"
                element={
                    <Layout>
                        <SignUp />
                    </Layout>
                }
            />
            <Route
                path="/login"
                element={
                    <Layout>
                        <Login />
                    </Layout>
                }
            />
            <Route
                path="/dashboard"
                element={
                    <Layout>
                        <Dashboard />
                    </Layout>
                }
            />
        </Routes>
    </BrowserRouter>
);

ReactDOM.createRoot(document.getElementById("react-root")).render(<App />);
