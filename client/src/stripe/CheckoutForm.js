import React from 'react';
import axios from 'axios';
import { CardElement, useStripe, useElements } from "@stripe/react-stripe-js";
import { useNavigate } from "react-router-dom";

export const CheckoutForm = () => {
    const stripe = useStripe();
    const elements = useElements();
    let navigate = useNavigate();

    const appearance = {
        theme: 'night',
        labels: 'floating'
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        const { error, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: elements.getElement(CardElement),
            appearance: appearance
        });
        if (error) {
            console.log("something went wrong");
        } else {
            console.log(paymentMethod);
            const { id } = paymentMethod;
            const { data } = await axios.post('/api/payment', { id });
            console.log(data);
        }
    }
    return (
        <>
            <form
                onSubmit={handleSubmit}
                style={{
                    width: "100%",
                }}
            >
                <CardElement
                    options={{
                        hidePostalCode: true,
                    }}
                />
                <button>payer</button>
            </form>
            <button
                className="btn btn-secondary"
                onClick={() => { navigate("") }}
            >
                Retour
            </button>
        </>
    );
};

export default CheckoutForm;