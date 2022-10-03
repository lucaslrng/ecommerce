import React, { useState } from 'react';
import FormAddress from '../components/MethodsAdrsPay/FormAddress';
import FormPayment from '../components/MethodsAdrsPay/FormPayment';
import Modal from '../components/Modal'
import AfterMadeAnAddress from '../components/MethodsAdrsPay/AfterMadeAnAddress';
import FormUpdateAddress from '../components/MethodsAdrsPay/FormUpdateAddress';
import { useSelector } from 'react-redux';

const Payment = () => {
    const [showModalAddress, setShowModalAddress] = useState(false);
    const [showModalPayment, setShowModalPayment] = useState(false);
    const [showModalUpdateAddress, setShowModalUpdateAddress] = useState(false);
    const [addressDataSet, setAddressDataSet] = useState(false)
    const addressData = useSelector((state) => state.addressReducer)
    const userData = useSelector((state) => state.userReducer)

    // React.useEffect(() => {
    //     if (addressData.user.id === userData.id) {
    //         setShowModalAddress(true);
    //     } else {
    //         setShowModalAddress(false);
    //     }
    // }
    //     , [])
    // a === true ? a : b === true ? b : c
    return (
        <>
            <div className='content__payment'>
                <div className='content__methode'>
                    <h2><strong>Carnet d’adresses</strong></h2>
                    <h3> Adresses de livraison et de facturation</h3>

                    <div className='cards-address'>
                        {
                        addressData.user.id === userData.id ? (
                            <AfterMadeAnAddress
                                modal={() => setShowModalUpdateAddress(true)}
                            />

                        ) : (
                            <div
                                className='add__address address__container'
                                onClick={() => setShowModalAddress(true)}
                            >
                                <span className='plus-icon'></span>
                                <span className='text-light'>Ajouter une adresse</span>
                            </div>
                        )}

                    </div>
                </div>
                <Modal
                    trigger={showModalAddress}
                    setTrigger={setShowModalAddress}
                    height='80'
                    width='50'
                >
                    <FormAddress
                        setAddressDataSet={setAddressDataSet}

                    />
                </Modal>
                <Modal
                    trigger={showModalUpdateAddress}
                    setTrigger={setShowModalUpdateAddress}
                    height='80'
                    width='50'
                >
                    <FormUpdateAddress
                        setAddressDataSet={setAddressDataSet}

                    />
                </Modal>
                <div className='content__methode'>
                    <h2><strong>Méthodes de paiement</strong></h2>
                    <h3> Cartes de crédit/ débit </h3>
                    <div
                        className='add__payment payment__container'
                        onClick={() => setShowModalPayment(true)}
                    >
                        <span className='plus-icon'></span>
                        <span className='text-light'>Ajouter une adresse</span>
                    </div>
                </div>
                <Modal
                    trigger={showModalPayment}
                    setTrigger={setShowModalPayment}
                    height='60'
                    width='50'
                >
                    <FormPayment />
                </Modal>
            </div>
        </>
    );
};

export default Payment;