import React, { useContext, useEffect, useRef, useState } from 'react'
import { UidContext } from '../Hook/AppContext';
import { IoMdLocate } from 'react-icons/io';
import { FloatingLabel, Form } from 'react-bootstrap';
import { useDispatch, useSelector } from 'react-redux';
import { UpdateAddress } from '../../actions/address.action';


const apiKey = "AIzaSyAc3iBBim1L35AhKwH_ganXOanpj12G1ck";
const mapApiJs = 'https://maps.googleapis.com/maps/api/js';
const geocodeJson = 'https://maps.googleapis.com/maps/api/geocode/json';



// load google map api js

function loadAsyncScript(src) {
    return new Promise(resolve => {
        const script = document.createElement("script");
        Object.assign(script, {
            type: "text/javascript",
            async: true,
            src
        })
        script.addEventListener("load", () => resolve(script));
        document.head.appendChild(script);
    })
}

const extractAddress = (place) => {

    const address = {
        street: '',
        route: '',
        city: "",
        state: "",
        zip: "",
        country: "",
        plain() {
            const route = this.route ? this.route + ", " : "";
            const street = this.street ? this.street + ", " : "";
            const city = this.city ? this.city + ", " : "";
            const zip = this.zip ? this.zip + ", " : "";
            const state = this.state ? this.state + ", " : "";
            return route + street + city + zip + state + this.country;
        }
    }

    if (!Array.isArray(place?.address_components)) {
        return address;
    }

    place.address_components.forEach(component => {
        const types = component.types;
        const value = component.long_name;
        if (types.includes("street_number")) {
            address.street = value;
        }
        if (types.includes("route")) {
            address.route = value;
        }

        if (types.includes("locality")) {
            address.city = value;
        }

        if (types.includes("administrative_area_level_2")) {
            address.state = value;
        }

        if (types.includes("postal_code")) {
            address.zip = value;
        }

        if (types.includes("country")) {
            address.country = value;
        }

    });

    return address;
}

function FormUpdateAddress(props) {

    const dispatch = useDispatch();
    const addressData = useSelector((state) => state.addressReducer)

    const searchInput = useRef(null);

    const [error, setError] = useState(null);
    const [address, setAddress] = useState({});
    const [myFirstname, setMyFirstname] = useState("");
    const [myLastname, setMyLastname] = useState("");
    const [myPhone, setMyPhone] = useState("");

    const token = useContext(UidContext);

    // init gmap script
    const initMapScript = () => {
        // if script already loaded
        if (window.google) {
            return Promise.resolve();
        }
        const src = `${mapApiJs}?key=${apiKey}&libraries=places&v=weekly`;
        return loadAsyncScript(src);
    }

    // do something on address change
    const onChangeAddress = (autocomplete) => {
        const place = autocomplete.getPlace();
        setAddress(extractAddress(place));
    }

    // init autocomplete
    const initAutocomplete = () => {
        if (!searchInput.current) return;

        const autocomplete = new window.google.maps.places.Autocomplete(searchInput.current);
        autocomplete.setFields(["address_component", "geometry"]);
        autocomplete.addListener("place_changed", () => onChangeAddress(autocomplete));

    }

    const reverseGeocode = ({ latitude: lat, longitude: lng }) => {
        const url = `${geocodeJson}?key=${apiKey}&latlng=${lat},${lng}`;
        searchInput.current.value = "Getting your location...";
        fetch(url)
            .then(response => response.json())
            .then(location => {
                const place = location.results[0];
                const _address = extractAddress(place);
                setAddress(_address);
                searchInput.current.value = _address.plain();
            })
    }

    const findMyLocation = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                reverseGeocode(position.coords)
            })
        }
    }

    // load map script after mounted
    useEffect(() => {
        initMapScript().then(() => initAutocomplete());
    });

    const handleUpdate = (e) => {
        e.preventDefault();
        if (
            myFirstname &&
            myLastname &&
            myPhone 
        ) {
            setError(null);
            if (
                addressData.firstname !== myFirstname ||
                addressData.lastname !== myLastname ||
                addressData.phone !== myPhone 
            ) {
                dispatch(UpdateAddress(token, myFirstname, myLastname, myPhone, address.street, address.route, address.city, address.state, address.zip, address.country));
                setError(null);
            } else {
                setError("aucune modification n'a été effectuée");
            }
        } else {
            setError("S'il vous plais remplissez tous les champs");
        }
    }
    return (
        <>
            <div className='content__api'>
                <h1>Ajouter une adresse</h1>
                <div className="form__address__api">
                    <div className="address">
                        <form >
                            <div className="form-group">

                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Phone Number"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Phone Number"
                                            value={myPhone}
                                            onChange={(e) => setMyPhone(e.target.value)}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">

                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Nom"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Nom"
                                            value={myFirstname}
                                            onChange={(e) => setMyFirstname(e.target.value)}
                                            required
                                        />
                                    </FloatingLabel>
                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Prénom"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Prénom"
                                            value={myLastname}
                                            onChange={(e) => setMyLastname(e.target.value)}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                {/* localisateur de google  */}
                                <div className='search-location'>
                                    <div className="search">
                                        <FloatingLabel
                                            controlId="floatingInput"
                                            label="localisateur d'adresse"
                                            className="mb-3 label-search"
                                        >
                                            <Form.Control
                                                className='input-search'
                                                ref={searchInput}
                                                type="text"
                                                placeholder="Search location...."
                                                required
                                            />
                                        </FloatingLabel>
                                        <button onClick={findMyLocation}><IoMdLocate /></button>
                                    </div>
                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="numéro de rue"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="number"
                                            placeholder="numéro de rue"
                                            value={address.street}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Votre rue"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Votre rue"
                                            value={address.route}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Ville"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Ville"
                                            value={address.city}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="code postal"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="code postal"
                                            value={address.zip}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Region"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Region"
                                            value={address.state}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                <div className="item-input">
                                    <FloatingLabel
                                        controlId="floatingInput"
                                        label="Pays"
                                        className="mb-3 "
                                    >
                                        <Form.Control
                                            type="text"
                                            placeholder="Pays"
                                            value={address.country}
                                            required
                                        />
                                    </FloatingLabel>

                                </div>
                                {error && <h3 className='fs-5' style={{ color: 'green' }}>{error}</h3>}
                                <button
                                    onClick={(e) => handleUpdate(e)}
                                    className="btn btn-block mt-3 mb-3 ms-auto me-auto btn-api"
                                >Validé</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    )

}
export default FormUpdateAddress;