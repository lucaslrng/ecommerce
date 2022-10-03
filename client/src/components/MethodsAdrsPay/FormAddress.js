import React from 'react';
import ApiGoogleAutocomplate from './ApiGoogleAutocomplate';
const FormAddress = (props) => {
    return (
        <div>
            <ApiGoogleAutocomplate
                setAddressDataSet={props.setAddressDataSet}
            />
        </div>
    );
};

export default FormAddress;