import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Home from '../../pages/Home';
import About from '../../pages/About';
import Profile from '../../pages/Profile';
import Items from '../../pages/Items';
import Catalog from '../../pages/Catalog';
import Nav from '../../components/Nav'
import Footer from '../../components/Footer'
import Carts from '../../pages/Carts';
import Order from '../../pages/Order';
import Payment from '../../pages/Payment';
import Services from '../../pages/Services';
import Contact from '../../pages/Contact';
import UserUpdate from '../UserUpdate';
import Article from '../../pages/Article';
import { CartProvider } from 'react-use-cart';


const App = () => {
  return (
    <CartProvider>

    <BrowserRouter>

      <Nav/>
      <Routes>
        <Route path='/' element={<Home/>} />
        <Route path='/à-propos' element={<About/>} />
        <Route path='/userAccount' element={<Profile/>} />
        <Route path='/items' element={<Items/>} />
        <Route path='/catalog' element={<Catalog/>} />
        <Route path='/panier' element={<Carts/>} />
        <Route path='/commande' element={<Order/>} />
        <Route path='/payment' element={<Payment/>} />
        <Route path='/nous-contacter' element={<Contact/>} />
        <Route path='/nos-services' element={<Services/>} />
        <Route path='/user-update' element={<UserUpdate/>} />
        <Route path='/article' element={<Article/>} />
        {/* path='*' fonctionne si jamais l'url ne correspond a rien de déclaré au dessus */}
        <Route path='*' element={<Home/>} />
      </Routes>
      <Footer/>

    </BrowserRouter>
    </CartProvider>

  );
}

export default App;