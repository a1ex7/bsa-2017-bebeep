import React from 'react';
import { localize } from 'react-localize-redux';

import { getVehiclePhoto } from 'app/services/PhotoService';

import "../styles/driver-profile.scss";

class DriverProfile extends React.Component {
    render() {
        const { car, translate } = this.props;

        return (
            <div className="driver-car-block">
                <p className="text-left"><strong>{translate('driver_public_profile.driver_car_header')} </strong></p>
                <div className="driver-car">
                    { getVehiclePhoto(car) }
                </div>
                <span>{translate('driver_public_profile.driver_car_model')}</span><span className="text-muted">{car.model}</span><br/>
                <span>{translate('driver_public_profile.driver_car_color')}</span><span className="text-muted">{car.color}</span><br/>
            </div>
        );
    }
}

export default localize(DriverProfile, 'locale');
